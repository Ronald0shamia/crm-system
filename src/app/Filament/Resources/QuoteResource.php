<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuoteResource\Pages;
use App\Models\Client;
use App\Models\Quote;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Angebote';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Angebot Details')
                ->schema([
                    Forms\Components\TextInput::make('quote_number')
                        ->label('Angebotsnummer')
                        ->disabled()
                        ->placeholder('Wird automatisch generiert'),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft'    => 'Entwurf',
                            'sent'     => 'Gesendet',
                            'accepted' => 'Akzeptiert',
                            'declined' => 'Abgelehnt',
                        ])
                        ->default('draft')
                        ->required(),
                    // lazy loading:
                    Forms\Components\Select::make('client_id')
                        ->label('Kunde')
                        ->relationship('client', 'company_name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\DatePicker::make('valid_until')
                        ->label('Gültig bis')
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Positionen')
                ->schema([
                    Forms\Components\Repeater::make('items')
                        ->relationship()
                        ->label('')
                        ->schema([
                            Forms\Components\TextInput::make('description')
                                ->label('Beschreibung')
                                ->required()
                                ->columnSpan(4),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Menge')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn(Get $get, Set $set) =>
                                    $set('total', round($get('quantity') * $get('unit_price'), 2))
                                )
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('unit_price')
                                ->label('Einzelpreis (€)')
                                ->numeric()
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn(Get $get, Set $set) =>
                                    $set('total', round($get('quantity') * $get('unit_price'), 2))
                                )
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('total')
                                ->label('Gesamt (€)')
                                ->numeric()
                                ->disabled()
                                ->columnSpan(2),
                        ])
                        ->columns(10)
                        ->addActionLabel('Position hinzufügen')
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::updateTotal($get, $set);
                        }),
                ]),

            Forms\Components\Section::make('Zusammenfassung')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notizen')
                        ->rows(3)
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('total')
                        ->label('Gesamtbetrag (€)')
                        ->numeric()
                        ->disabled()
                        ->columnSpan(1),
                ])->columns(3),
        ]);
    }

    protected static function updateTotal(Get $get, Set $set): void
    {
        $items = $get('items') ?? [];
        $total = collect($items)->sum(fn($item) => $item['total'] ?? 0);
        $set('total', round($total, 2));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('quote_number')
                    ->label('Nummer')
                    ->searchable()->sortable(),
                Tables\Columns\TextColumn::make('client.company_name')
                    ->label('Kunde')
                    ->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray'    => 'draft',
                        'info'    => 'sent',
                        'success' => 'accepted',
                        'danger'  => 'declined',
                    ])
                    ->formatStateUsing(fn(string $state): string => match($state) {
                        'draft'    => 'Entwurf',
                        'sent'     => 'Gesendet',
                        'accepted' => 'Akzeptiert',
                        'declined' => 'Abgelehnt',
                        default    => $state,
                    }),
                Tables\Columns\TextColumn::make('total')
                    ->label('Betrag')
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('valid_until')
                    ->label('Gültig bis')
                    ->date('d.m.Y')->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Erstellt')
                    ->date('d.m.Y')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft'    => 'Entwurf',
                        'sent'     => 'Gesendet',
                        'accepted' => 'Akzeptiert',
                        'declined' => 'Abgelehnt',
                    ]),
                Tables\Filters\SelectFilter::make('client')
                    ->label('Kunde')
                    ->relationship('client', 'company_name'),
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
            'index'  => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'edit'   => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}