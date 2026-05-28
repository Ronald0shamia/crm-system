<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WordpressSiteResource\Pages;
use App\Models\WordpressSite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WordpressSiteResource extends Resource
{
    protected static ?string $model = WordpressSite::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $navigationGroup = 'Websites';
    protected static ?string $navigationLabel = 'WordPress';
    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Website')
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
                    Forms\Components\TextInput::make('admin_url')
                        ->label('Admin URL')
                        ->url()
                        ->maxLength(255),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options(self::statusOptions())
                        ->default('pending')
                        ->required(),
                    Forms\Components\TextInput::make('wordpress_version')
                        ->label('WordPress Version'),
                    Forms\Components\DateTimePicker::make('last_checked_at')
                        ->label('Zuletzt geprueft'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktiv')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Plugins und Themes')
                ->schema([
                    Forms\Components\TextInput::make('plugins_count')
                        ->label('Plugins')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('outdated_plugins_count')
                        ->label('Veraltete Plugins')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('themes_count')
                        ->label('Themes')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('outdated_themes_count')
                        ->label('Veraltete Themes')
                        ->numeric()
                        ->default(0),
                    Forms\Components\KeyValue::make('plugins')
                        ->label('Plugin Status')
                        ->keyLabel('Plugin')
                        ->valueLabel('Version / Status')
                        ->columnSpanFull(),
                    Forms\Components\KeyValue::make('themes')
                        ->label('Theme Status')
                        ->keyLabel('Theme')
                        ->valueLabel('Version / Status')
                        ->columnSpanFull(),
                ])->columns(4),

            Forms\Components\Section::make('Notizen')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notizen')
                        ->rows(3),
                ]),
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
                Tables\Columns\TextColumn::make('wordpress_version')
                    ->label('WP'),
                Tables\Columns\TextColumn::make('outdated_plugins_count')
                    ->label('Plugin Updates')
                    ->sortable(),
                Tables\Columns\TextColumn::make('outdated_themes_count')
                    ->label('Theme Updates')
                    ->sortable(),
                Tables\Columns\TextColumn::make('last_checked_at')
                    ->label('Zuletzt')
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
            'index' => Pages\ListWordpressSites::route('/'),
            'create' => Pages\CreateWordpressSite::route('/create'),
            'edit' => Pages\EditWordpressSite::route('/{record}/edit'),
        ];
    }

    public static function statusOptions(): array
    {
        return [
            'pending' => 'Ausstehend',
            'healthy' => 'OK',
            'updates_available' => 'Updates offen',
            'attention' => 'Pruefen',
            'error' => 'Fehler',
        ];
    }

    public static function statusColor(string $state): string
    {
        return match ($state) {
            'healthy' => 'success',
            'updates_available' => 'warning',
            'attention' => 'info',
            'error' => 'danger',
            default => 'gray',
        };
    }
}
