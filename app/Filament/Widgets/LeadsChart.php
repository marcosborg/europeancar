<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;

class LeadsChart extends ChartWidget
{
    protected ?string $heading = 'Leads por mês';

    protected function getData(): array
    {
        $period = CarbonPeriod::create(now()->subMonths(11)->startOfMonth(), '1 month', now()->startOfMonth());
        $labels = [];
        $data = [];

        foreach ($period as $month) {
            $labels[] = $month->format('M Y');
            $data[] = Lead::query()
                ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Leads',
                    'data' => $data,
                    'borderColor' => '#002B6B',
                    'backgroundColor' => '#F7B500',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
