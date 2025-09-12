<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\UserResource\Widgets\TopKeywordsChart::make(['record' => $this->record]),
            \App\Filament\Resources\UserResource\Widgets\TopCartProductsChart::make(['record' => $this->record]),
            \App\Filament\Resources\UserResource\Widgets\TopOrderedProductsChart::make(['record' => $this->record]),
            \App\Filament\Resources\UserResource\Widgets\UserTotalSpent::make(['record' => $this->record]),
        ];
    }
}