<?php

namespace Modules\HomeModule\App\Livewire\Components;

use Livewire\Component;
use App\Models\Flashsale as FlashsaleModel;

class FlashSale extends Component
{
    public $products = [];
    public $expiredAt;

    public function mount()
    {
        $flashSales = FlashsaleModel::active()
            ->where('expired_at', '<=', now()->addHours(24))
            ->where('expired_at', '>', now())
            ->with(['skus.product', 'discount'])
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
                ];
            }
        }

        $this->products = $mergedProducts;
    }

    public function render()
    {
        return view('homemodule::livewire.components.flash-sale');
    }
}
