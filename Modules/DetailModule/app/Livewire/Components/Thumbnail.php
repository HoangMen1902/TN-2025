<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\On; 


class Thumbnail extends Component
{

    public $thumbnail;
    public $currentSku;
    public $data;

    public $type;

    public function mount() {
        if($this->type === "product") {
            $this->currentSku = $this->data->productSkus->first();
            $this->thumbnail = $this->currentSku->images[0];
        } elseif ($this->type === "combo") {
            $this->thumbnail = $this->data->images[0];
        }

    }


    #[On('updatedSku')]
    public function updateSku($skuId) {
        $currentSku = $this->data->productSkus->firstWhere('id', $skuId);
        $this->currentSku = $currentSku;
        $this->thumbnail = $this->currentSku->images[0];
    } 
    public function render()
    {

        return view('detailmodule::livewire.components.thumbnail');
    }

}
