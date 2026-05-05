<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Pedido')->columns(3)->schema([
                Select::make('type')->required()->options([
                    'contact' => 'Contacto',
                    'financing' => 'Financiamento',
                    'test_drive' => 'Test-drive',
                    'rental' => 'Aluguer',
                    'reservation' => 'Reserva',
                    'information' => 'Informação',
                ]),
                Select::make('status')->required()->options([
                    'new' => 'Novo',
                    'reviewing' => 'Em análise',
                    'contacted' => 'Contactado',
                    'closed' => 'Fechado',
                    'lost' => 'Perdido',
                ])->default('new'),
                Select::make('vehicle_id')->relationship('vehicle', 'sku')->searchable()->preload(),
                Select::make('assigned_to')->relationship('assignedUser', 'name')->searchable()->preload(),
                TextInput::make('subject')->maxLength(255),
            ]),
            Section::make('Cliente')->columns(3)->schema([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->maxLength(255),
                TextInput::make('phone')->tel()->maxLength(255),
                TextInput::make('country')->maxLength(255),
                TextInput::make('city')->maxLength(255),
            ]),
            Section::make('Detalhes')->columns(3)->schema([
                TextInput::make('down_payment')->numeric()->prefix('€'),
                TextInput::make('desired_term_months')->numeric(),
                DatePicker::make('rental_start_date'),
                DatePicker::make('rental_end_date'),
                Textarea::make('message')->columnSpanFull(),
                Textarea::make('internal_notes')->columnSpanFull(),
            ]),
        ]);
    }
}
