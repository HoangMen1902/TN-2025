<?php

namespace App\Filament\Resources\ShipmentUnitResource\Pages;

use App\Filament\Resources\ShipmentUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShipmentUnit extends EditRecord
{
    protected static string $resource = ShipmentUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
