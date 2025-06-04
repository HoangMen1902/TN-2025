<?php

namespace App\Filament\Resources\ShipmentUnitResource\Pages;

use App\Filament\Resources\ShipmentUnitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ListShipmentUnits extends ListRecords implements HasTable
{
    protected static string $resource = ShipmentUnitResource::class;

    
    
}
