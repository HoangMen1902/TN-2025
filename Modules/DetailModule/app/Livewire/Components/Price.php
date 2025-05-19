<?php

namespace Modules\DetailModule\Livewire\Components;

use Livewire\Component;
use Livewire\Attributes\On;

class Price extends Component
{

    public $data;
    public $sale_price;
    public $price;
    public $sale_percent;

    public function mount() {
        $this->price = $this->data->productSkus->first()->price;
        $this->sale_price = $this->data->productSkus->first()->sale_price;
        $this->sale_percent = (1 - $this->sale_price/$this->price) * 100;
    }

    #[On('updatedSku')]
    public function updateSku($skuId) {
        $currentSku = $this->data->productSkus->firstWhere('id', $skuId);
        $this->sale_price = $currentSku->sale_price;
        $this->price = $currentSku->price;
        $this->sale_percent = (1 - $this->sale_price/$this->price) * 100;
    }


    public function render()
    {
        return view('detailmodule::livewire.components.price');
    }
}
