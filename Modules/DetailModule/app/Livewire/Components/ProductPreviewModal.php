<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\On;

class ProductPreviewModal extends Component
{

    public $data;
    public $isOpen = false;
    public $previewUrl = null;
    public $productTitle;

    protected $listeners = ['openPreviewModal'];
    public function openPreviewModal($productId)
    {
        $product = Product::with('preview')->find($productId);

        if ($product && $product->preview) {
            $this->previewUrl = asset('storage/' . $product->preview->file_path);
            $this->productTitle = $product->name;
            $this->isOpen = true;
            $this->dispatch('toogleContent', false);
        }
    }




    public function closeModal()
    {
        $this->isOpen = false;
        $this->previewUrl = null;
        $this->productTitle = null;
        $this->dispatch('toogleContent', true);
    }

    public function render()
    {
        return view('detailmodule::livewire.components.product-preview-modal');
    }
}
