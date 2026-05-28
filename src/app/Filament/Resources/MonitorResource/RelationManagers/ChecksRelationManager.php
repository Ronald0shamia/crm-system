<?php

namespace App\Filament\Resources\MonitorResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ChecksRelationManager extends RelationManager
{
    protected static string $relationship = 'checks';
    protected static ?string $title = 'Verlauf';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'up' => 'Online',
                    'down' => 'Offline',
                    'degraded' => 'Langsam',
                ])
                ->required(),
            Forms\Components\TextInput::make('status_code')
                ->label('Statuscode')
                ->numeric(),
            Forms\Components\TextInput::make('response_time_ms')
                ->label('Antwortzeit (ms)')
                ->numeric(),
            Forms\Components\DateTimePicker::make('checked_at')
                ->label('Geprueft am')
                ->default(now())
                ->required(),
            Forms\Components\Textarea::make('message')
                ->label('Meldung')
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('checked_at')
                    ->label('Zeitpunkt')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'up' => 'success',
                        'down' => 'danger',
                        'degraded' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status_code')
                    ->label('Code'),
                Tables\Columns\TextColumn::make('response_time_ms')
                    ->label('ms'),
                Tables\Columns\TextColumn::make('message')
                    ->label('Meldung')
                    ->limit(60),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
