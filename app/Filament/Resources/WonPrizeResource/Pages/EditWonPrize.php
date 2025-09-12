<?php

namespace App\Filament\Resources\WonPrizeResource\Pages;

use App\Filament\Resources\WonPrizeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWonPrize extends EditRecord
{
    protected static string $resource = WonPrizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
