<?php

namespace App\Services\SystemTools;

use App\Models\SystemToolRun;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PDO;
use RuntimeException;
use Throwable;

class DatabaseSyncService
{
    public function run(SystemToolRun $run): void
    {
        if (app(EnvironmentSwitcher::class)->isProduction()) {
            throw new RuntimeException('Database sync is blocked while the application environment is production.');
        }

        $this->assertConnectionsAreConfigured();
        $this->assertConnectionsAreDifferent();

        $startedAt = hrtime(true);

        $run->update([
            'status' => 'running',
            'started_at' => now(),
        ]);

        $output = [];

        try {
            $production = DB::connection('system_tools_production');
            $sandbox = DB::connection('system_tools_sandbox');

            $productionPdo = $production->getPdo();
            $sandboxPdo = $sandbox->getPdo();

            $sandboxPdo->exec('SET FOREIGN_KEY_CHECKS=0');

            $sandboxTables = $this->tableNames($sandboxPdo);

            foreach ($sandboxTables as $table) {
                $sandboxPdo->exec('DROP TABLE IF EXISTS '.$this->quoteIdentifier($table));
            }

            $productionTables = $this->tableNames($productionPdo);
            $output[] = 'Dropped '.count($sandboxTables).' sandbox tables.';

            foreach ($productionTables as $table) {
                $createStatement = $this->createTableStatement($productionPdo, $table);
                $sandboxPdo->exec($createStatement);
            }

            $output[] = 'Created '.count($productionTables).' tables from production schema.';

            $totalRows = 0;

            foreach ($productionTables as $table) {
                $rows = $this->copyTableRows($production, $sandbox, $table);
                $totalRows += $rows;
                $output[] = "{$table}: {$rows} rows";

                $run->update([
                    'output' => mb_substr(implode(PHP_EOL, $output), 0, 10000),
                ]);
            }

            $sandboxPdo->exec('SET FOREIGN_KEY_CHECKS=1');

            $output[] = "Imported {$totalRows} rows into sandbox.";

            $run->update([
                'status' => 'succeeded',
                'exit_code' => 0,
                'output' => mb_substr(implode(PHP_EOL, $output), 0, 10000),
                'duration_ms' => $this->durationInMilliseconds($startedAt),
                'finished_at' => now(),
            ]);
        } catch (Throwable $exception) {
            try {
                DB::connection('system_tools_sandbox')->getPdo()->exec('SET FOREIGN_KEY_CHECKS=1');
            } catch (Throwable) {
                //
            }

            $run->update([
                'status' => 'failed',
                'error' => mb_substr($exception->getMessage(), 0, 10000),
                'duration_ms' => $this->durationInMilliseconds($startedAt),
                'finished_at' => now(),
            ]);

            throw $exception;
        } finally {
            DB::purge('system_tools_production');
            DB::purge('system_tools_sandbox');
        }
    }

    /**
     * @return array<int, string>
     */
    private function tableNames(PDO $pdo): array
    {
        $statement = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");

        return array_map(
            static fn (array $row): string => (string) $row[0],
            $statement->fetchAll(PDO::FETCH_NUM),
        );
    }

    private function createTableStatement(PDO $pdo, string $table): string
    {
        $statement = $pdo->query('SHOW CREATE TABLE '.$this->quoteIdentifier($table));
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if (! $row) {
            throw new RuntimeException("Unable to read create statement for table [{$table}].");
        }

        return (string) $row['Create Table'];
    }

    private function copyTableRows(ConnectionInterface $production, ConnectionInterface $sandbox, string $table): int
    {
        $select = $production->getPdo()->query('SELECT * FROM '.$this->quoteIdentifier($table));
        $insertedRows = 0;

        while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
            $columns = array_keys($row);
            $columnSql = implode(', ', array_map(fn (string $column): string => $this->quoteIdentifier($column), $columns));
            $placeholders = implode(', ', array_fill(0, count($columns), '?'));

            $sandbox->insert(
                'INSERT INTO '.$this->quoteIdentifier($table)." ({$columnSql}) VALUES ({$placeholders})",
                array_values($row),
            );

            $insertedRows++;
        }

        return $insertedRows;
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`'.str_replace('`', '``', $identifier).'`';
    }

    private function assertConnectionsAreConfigured(): void
    {
        foreach (['system_tools_production', 'system_tools_sandbox'] as $connection) {
            $config = Config::get("database.connections.{$connection}");

            foreach (['host', 'database', 'username'] as $key) {
                if (blank($config[$key] ?? null)) {
                    throw new RuntimeException("Database connection [{$connection}] is missing [{$key}].");
                }
            }
        }
    }

    private function assertConnectionsAreDifferent(): void
    {
        $production = Config::get('database.connections.system_tools_production');
        $sandbox = Config::get('database.connections.system_tools_sandbox');

        foreach (['host', 'port', 'database', 'username'] as $key) {
            if (($production[$key] ?? null) !== ($sandbox[$key] ?? null)) {
                return;
            }
        }

        throw new RuntimeException('Production and sandbox database connections point to the same database.');
    }

    private function durationInMilliseconds(int $startedAt): int
    {
        return (int) round((hrtime(true) - $startedAt) / 1_000_000);
    }
}
