<?php

namespace App\Filament\Resources\WordpressSiteResource\Pages;

use App\Filament\Resources\WordpressSiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWordpressSites extends ListRecords
{
    protected static string $resource = WordpressSiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
