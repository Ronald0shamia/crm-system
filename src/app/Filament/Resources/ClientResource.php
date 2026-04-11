<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Kunden';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Firmendetails')
                ->schema([
                    Forms\Components\TextInput::make('company_name')
                        ->label('Firmenname')
                        ->required()->maxLength(255),
                    Forms\Components\Select::make('type')
                        ->label('Typ')
                        ->options([
                            'agency'     => 'Agentur',
                            'freelancer' => 'Freelancer',
                            'company'    => 'Unternehmen',
                        ])->required(),
                ])->columns(2),

            Forms\Components\Section::make('Kontaktdaten')
                ->schema([
                    Forms\Components\TextInput::make('email')
                        ->label('E-Mail')
                        ->email()->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label('Telefon'),
                    Forms\Components\TextInput::make('website')
                        ->label('Website')
                        ->url()->prefix('https://'),
                    Forms\Components\Textarea::make('address')
                        ->label('Adresse')
                        ->rows(3)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Firmenname')
                    ->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Typ')
                    ->colors([
                        'primary' => 'agency',
                        'success' => 'freelancer',
                        'warning' => 'company',
                    ]),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon'),
                Tables\Columns\TextColumn::make('contacts_count')
                    ->label('Kontakte')
                    ->counts('contacts'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Erstellt')
                    ->date('d.m.Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Typ')
                    ->options([
                        'agency'     => 'Agentur',
                        'freelancer' => 'Freelancer',
                        'company'    => 'Unternehmen',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit'   => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}