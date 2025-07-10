<?php

namespace Modules\HomeModule\Livewire\Components;

use App\Models\Flashsale;
use Livewire\Component;

class FlashsaleProduct extends Component
{
    public $products = [];
    public $expiredAt;

    public function mount()
    {
        $flashSales = Flashsale::active()
            ->where('expired_at', '<=', now()->addHours(24))
            ->where('expired_at', '>', now())
            ->with(['skus.product'])
            ->get();

        if ($flashSales->isEmpty()) {
            $this->products = [];
            $this->expiredAt = null;
            return;
        }
        $this->expiredAt = $flashSales->min('expired_at');

        $mergedProducts = [];

        foreach ($flashSales as $fs) {
            $discount = $fs->discount;

            foreach ($fs->skus as $sku) {
                if (!$sku || !$sku->product) continue;

                $originalPrice = $sku->price;
                $basePrice = $sku->sale_price ?? $originalPrice;

                if ($discount) {
                    if ($discount->discount_type === 'percent') {
                        $finalSalePrice = $basePrice * (1 - $discount->discount_amount / 100);
                    } else {
                        $finalSalePrice = $basePrice - $discount->discount_amount;
                    }
                    $finalSalePrice = max(0, $finalSalePrice); 
                } else {
                    $finalSalePrice = $basePrice;
                }

                $percentSold = ($sku->quantity ?? 0) > 0
                    ? round(($sku->sold ?? 0) / $sku->quantity * 100)
                    : 0;

                $mergedProducts[] = [
                    'name' => $sku->product->name,
                    'image' => $sku->product->thumbnail,
                    'price' => $originalPrice,
                    'sale_price' => $finalSalePrice,
                    'discount_percent' => $originalPrice > 0
                        ? round(($originalPrice - $finalSalePrice) / $originalPrice * 100)
                        : 0,
                    'sold' => $sku->sold ?? 10,
                    'total' => $sku->quantity ?? 0,
                    'percent_sold' => $percentSold,
                    'slug' => $sku->product->slug
                ];
            }
        }

        $this->products = $mergedProducts;
    }

    public function render()
    {
        return view('homemodule::livewire.components.flashsale-product');
    }
}
