<?php

namespace App\Filament\Resources\FlashSaleResource\Pages;

use App\Filament\Resources\FlashSaleResource;
use App\Models\FlashsaleProduct;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFlashSale extends CreateRecord
{
    protected static string $resource = FlashSaleResource::class;
    protected function afterCreate(): void
    {
        $recordId = $this->record->id;
        $formData = $this->form->getState();

        $selected_skus = $formData['selected_skus'] ?? [];


        $now = now();

        $insertData = [];



        foreach ($selected_skus as $sku) {
            $insertData[] = ['sku_id' => $sku, 'flashsale_id' => $recordId, 'created_at' => now(), 'updated_at' => now()];
        }

        FlashsaleProduct::insert($insertData);
    }
}
