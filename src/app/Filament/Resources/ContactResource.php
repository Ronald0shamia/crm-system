<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Models\Client;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Kontakte';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Kontaktperson')
                ->schema([
                    Forms\Components\Select::make('client_id')
                        ->label('Kunde')
                        ->relationship('client', 'company_name')
                        ->searchable()
                        ->preload()
                        ->searchable()->required(),
                    Forms\Components\TextInput::make('position')
                        ->label('Position'),
                    Forms\Components\TextInput::make('first_name')
                        ->label('Vorname')->required(),
                    Forms\Components\TextInput::make('last_name')
                        ->label('Nachname')->required(),
                    Forms\Components\TextInput::make('email')
                        ->label('E-Mail')->email(),
                    Forms\Components\TextInput::make('phone')
                        ->label('Telefon'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('Vorname')->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->label('Nachname')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Kunde')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->label('Position'),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail'),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefon'),
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
            'index'  => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit'   => Pages\EditContact::route('/{record}/edit'),
        ];
    }
}