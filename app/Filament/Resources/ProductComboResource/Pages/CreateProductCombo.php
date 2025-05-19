<?php

namespace App\Filament\Resources\ProductComboResource\Pages;

use App\Filament\Resources\ProductComboResource;
use App\Models\ComboSku;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;


class CreateProductCombo extends CreateRecord
{
    protected static string $resource = ProductComboResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $record = $this->record;            
    //     $data = $this->form->getState();   
    //     // dd($data);
    //     return $data;
    // }
    
    


    protected function afterCreate(): void
    {
        $record = $this->record;            
        $data = $this->form->getState();     
        foreach ($data['combo_items'] ?? [] as $item) {
            if (!empty($item['selected']) && !empty($item['sku_id'])) {
                ComboSku::create([
                    'combo_id' => $record->id,
                    'sku_id' => $item['sku_id'],
                    // 'quantity' => $item['sku_quantity'] ?? 1,
                ]);
            }
        }
    }


}
