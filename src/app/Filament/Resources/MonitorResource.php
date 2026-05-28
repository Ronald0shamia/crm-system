<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitorResource\Pages;
use App\Filament\Resources\MonitorResource\RelationManagers\ChecksRelationManager;
use App\Models\Monitor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MonitorResource extends Resource
{
    protected static ?string $model = Monitor::class;
    protected static ?string $navigationIcon = 'heroicon-o-signal';
    protected static ?string $navigationGroup = 'Monitoring';
    protected static ?string $navigationLabel = 'Monitors';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Monitor')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('client_id')
                        ->label('Kunde')
                        ->relationship('client', 'company_name')
                        ->searchable()
                        ->preload(),
                    Forms\Components\TextInput::make('url')
                        ->label('URL')
                        ->url()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('method')
                        ->label('Methode')
                        ->options([
                            'GET' => 'GET',
                            'HEAD' => 'HEAD',
                            'POST' => 'POST',
                        ])
                        ->default('GET')
                        ->required(),
                    Forms\Components\TextInput::make('expected_status_code')
                        ->label('Erwarteter Statuscode')
                        ->numeric()
                        ->default(200)
                        ->required(),
                    Forms\Components\TextInput::make('check_interval_minutes')
                        ->label('Intervall in Minuten')
                        ->numeric()
                        ->default(5)
                        ->required(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktiv')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Status')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(self::statusOptions())
                        ->default('pending')
                        ->required(),
                    Forms\Components\TextInput::make('last_status_code')
                        ->label('Letzter Statuscode')
                        ->numeric(),
                    Forms\Components\TextInput::make('last_response_time_ms')
                        ->label('Antwortzeit (ms)')
                        ->numeric(),
                    Forms\Components\DateTimePicker::make('last_checked_at')
                        ->label('Zuletzt geprueft'),
                    Forms\Components\DateTimePicker::make('last_success_at')
                        ->label('Letzter Erfolg'),
                    Forms\Components\DateTimePicker::make('last_failure_at')
                        ->label('Letzter Fehler'),
                    Forms\Components\Textarea::make('last_error')
                        ->label('Fehlermeldung')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Kunde')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->limit(45),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => self::statusColor($state))
                    ->formatStateUsing(fn (string $state): string => self::statusOptions()[$state] ?? $state),
                Tables\Columns\TextColumn::make('last_status_code')
                    ->label('Code')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_response_time_ms')
                    ->label('ms')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_checked_at')
                    ->label('Zuletzt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktiv')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(self::statusOptions()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Aktiv'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ChecksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMonitors::route('/'),
            'create' => Pages\CreateMonitor::route('/create'),
            'edit' => Pages\EditMonitor::route('/{record}/edit'),
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Ausstehend',
            'up' => 'Online',
            'down' => 'Offline',
            'degraded' => 'Langsam',
        ];
    }

    public static function statusColor(string $state): string
    {
        return match ($state) {
            'up' => 'success',
            'down' => 'danger',
            'degraded' => 'warning',
            default => 'gray',
        };
    }
}
