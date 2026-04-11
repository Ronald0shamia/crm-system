<?php

namespace App\Filament\Resources\QuoteItemResource\Pages;

use App\Filament\Resources\QuoteItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQuoteItems extends ListRecords
{
    protected static string $resource = QuoteItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
