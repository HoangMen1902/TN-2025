<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\ProductCombo;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class Price extends Component
{
    public $data;
    public $price;
    public $sale_price;
    public $sale_percent;
    public $hasFlashSale = false;
    public $type;

    public function mount()
    {
        if ($this->type === "product") {
            $this->loadPriceFromSku($this->data->productSkus->first()->id);
        } elseif ($this->type === "combo") {
            $this->loadPriceFromCombo($this->data->id);
        }
    }

    public function loadPriceFromCombo($comboId)
    {
        $combo = ProductCombo::find($comboId);

        if ($combo) {
            $this->sale_price = $combo->sale_price;
            $this->price = $combo->original_price;
        } else {
            return;
        }
    }


    #[On('updatedSku')]
    public function updateSku($skuId)
    {
        $this->hasFlashSale = false;
        $this->loadPriceFromSku($skuId);
    }

    #[On('flashsaleUpdated')]
    public function handleFlashsaleUpdated()
    {
        $skuId = $this->data->productSkus->first()->id;
        $sku = $this->data->productSkus->firstWhere('id', $skuId);

        $this->price = $sku->price;

        $flashSale = \App\Models\FlashsaleProduct::with('flashsale')
            ->where('sku_id', $skuId)
            ->whereHas('flashsale', function ($q) {
                $q->where('started_at', '<=', now())
                    ->where('expired_at', '>=', now());
            })
            ->first();

        if ($flashSale && $flashSale->flashsale && $flashSale->flashsale->discount_amount) {
            $discount = $flashSale->flashsale;
            $base = $sku->sale_price ?? $sku->price;

            if ($discount->discount_type === 'percent') {
                $this->sale_price = round($base * (1 - $discount->discount_amount / 100));
            } elseif ($discount->discount_type === 'specific') {
                $this->sale_price = $base - $discount->discount_amount;
            } else {

                $this->sale_price = $base;
            }
            $this->hasFlashSale = true;
        } else {
            $this->sale_price = $sku->sale_price ?? $sku->price;
            $this->hasFlashSale = false;
        }
        $this->sale_percent = $this->price > 0
            ? (1 - $this->sale_price / $this->price) * 100
            : 0;
    }


    private function loadPriceFromSku($skuId)
    {
        $sku = $this->data->productSkus->firstWhere('id', $skuId);

        $this->price = $sku->price;

        $flashSale = \App\Models\FlashsaleProduct::with('flashsale')
            ->where('sku_id', $skuId)
            ->whereHas('flashsale', function ($q) {
                $q->where('started_at', '<=', now())
                    ->where('expired_at', '>=', now());
            })
            ->first();

        if ($flashSale && $flashSale->flashsale && $flashSale->flashsale->discount) {
            $discount = $flashSale->flashsale->discount;
            $base = $sku->sale_price ?? $sku->price;

            if ($discount->discount_type === 'percent') {
                $this->sale_price = round($base * (1 - $discount->discount_amount / 100));
            } elseif ($discount->discount_type === 'specific') {
                $this->sale_price = $base - $discount->discount_amount;
            } else {
                $this->sale_price = $base;
            }

            $this->hasFlashSale = true;
        } else {
            $this->sale_price = $sku->sale_price ?? $sku->price;
            $this->hasFlashSale = false;
        }

        $this->sale_percent = $this->price > 0
            ? (1 - $this->sale_price / $this->price) * 100
            : 0;
    }

    public function render()
    {
        return view('detailmodule::livewire.components.price');
    }
}
