<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoogleAccountResource\Pages;
use App\Models\GoogleAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GoogleAccountResource extends Resource
{
    protected static ?string $model = GoogleAccount::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?string $navigationLabel = 'Google Accounts';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Account')
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
                    Forms\Components\TextInput::make('email')
                        ->label('Google E-Mail')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(self::statusOptions())
                        ->default('pending')
                        ->required(),
                    Forms\Components\CheckboxList::make('enabled_services')
                        ->label('Dienste')
                        ->options([
                            'ads' => 'Google Ads',
                            'analytics' => 'Analytics',
                            'search_console' => 'Search Console',
                        ])
                        ->columns(3)
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktiv')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Verknuepfungen')
                ->schema([
                    Forms\Components\TextInput::make('ads_customer_id')
                        ->label('Google Ads Customer ID'),
                    Forms\Components\TextInput::make('analytics_property_id')
                        ->label('Analytics Property ID'),
                    Forms\Components\TextInput::make('search_console_site_url')
                        ->label('Search Console Property')
                        ->url(),
                    Forms\Components\DateTimePicker::make('connected_at')
                        ->label('Verbunden am'),
                    Forms\Components\DateTimePicker::make('last_synced_at')
                        ->label('Zuletzt synchronisiert'),
                    Forms\Components\Textarea::make('notes')
                        ->label('Notizen')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),
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
                Tables\Columns\TextColumn::make('email')
                    ->label('E-Mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => self::statusColor($state))
                    ->formatStateUsing(fn (string $state): string => self::statusOptions()[$state] ?? $state),
                Tables\Columns\TextColumn::make('ads_customer_id')
                    ->label('Ads'),
                Tables\Columns\TextColumn::make('analytics_property_id')
                    ->label('Analytics'),
                Tables\Columns\TextColumn::make('search_console_site_url')
                    ->label('Search Console')
                    ->limit(30),
                Tables\Columns\TextColumn::make('last_synced_at')
                    ->label('Sync')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGoogleAccounts::route('/'),
            'create' => Pages\CreateGoogleAccount::route('/create'),
            'edit' => Pages\EditGoogleAccount::route('/{record}/edit'),
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Ausstehend',
            'connected' => 'Verbunden',
            'needs_auth' => 'Login noetig',
            'sync_error' => 'Sync Fehler',
        ];
    }

    public static function statusColor(string $state): string
    {
        return match ($state) {
            'connected' => 'success',
            'needs_auth' => 'warning',
            'sync_error' => 'danger',
            default => 'gray',
        };
    }
}
