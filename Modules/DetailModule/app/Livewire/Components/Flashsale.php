<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\FlashsaleProduct;
use Carbon\Carbon;
use Livewire\Component;

class Flashsale extends Component
{
    public $skuId;
    public $flashPrice;
    public $flashSaleProduct;

    public function mount($skuId)
    {
        $this->skuId = $skuId;
        $this->checkFlashSale();
    }

    public function checkFlashSale()
    {
        $now = Carbon::now();

        $flashSaleProduct = FlashsaleProduct::with(['flashsale.discount', 'sku'])
            ->where('sku_id', $this->skuId)
            ->whereHas('flashsale', function ($q) use ($now) {
                $q->where('started_at', '<=', $now)
                    ->where('expired_at', '>=', $now);
            })
            ->first();

        if ($flashSaleProduct) {
            $this->flashSaleProduct = $flashSaleProduct;

            $sku = $flashSaleProduct->sku;
            $discount = $flashSaleProduct->flashsale->discount;
            $originalPrice = $sku->price;

            if ($discount) {
                if ($discount->discount_type === 'percent') {
                    $this->flashPrice = round($originalPrice * (1 - $discount->discount_amount / 100));
                } elseif ($discount->discount_type === 'specific') {
                    $this->flashPrice = $discount->discount_amount;
                } else {
                    $this->flashPrice = $originalPrice;
                }
            } else {
                $this->flashPrice = $originalPrice;
            }

          $this->dispatch('flashsaleUpdated');

        } else {
            $this->flashSaleProduct = null;
            $this->flashPrice = null;
        }
    }

    public function render()
    {
        $data = null;

        if ($this->flashSaleProduct) {
            $sku = $this->flashSaleProduct->sku;
            $flashsale = $this->flashSaleProduct->flashsale;

            $data = [
                'startTime' => Carbon::parse($flashsale->started_at)->toIso8601String(),
                'endTime' => Carbon::parse($flashsale->expired_at)->toIso8601String(),

                'sold' => $sku->sold ?? 0,
                'quantity' => $sku->quantity ?? 0,
                'flashPrice' => $this->flashPrice,
            ];
        }

        return view('detailmodule::livewire.components.flashsale', compact('data'));
    }
}
