<?php

namespace App\Filament\Resources\WonPrizeResource\Pages;

use App\Filament\Resources\WonPrizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWonPrizes extends ListRecords
{
    protected static string $resource = WonPrizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
