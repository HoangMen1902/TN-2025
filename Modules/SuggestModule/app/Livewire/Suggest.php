<?php

namespace Modules\SuggestModule\Livewire;

use App\Models\Product;
use Livewire\Component;

class Suggest extends Component
{
    public $products;
    public $displayLimit = 10;
    public $maxLimit = 70;
    public $showAll = false;

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Product::with(['productSkus', 'categories'])
            ->whereHas('productSkus')
            ->inRandomOrder()
            ->limit($this->maxLimit)
            ->get()
            ->map(function ($product) {
                $firstSku = $product->productSkus->first();

                $price = $firstSku ? $firstSku->price : 0;
                $sale_price = $firstSku ? ($firstSku->sale_price ?? $price) : 0;

                $discount = 0;
                if ($price > $sale_price && $price > 0) {
                    $discount = round((($price - $sale_price) / $price) * 100);
                }
                $total = $product->productSkus->sum('quantity');
                $sold = $total > 0 ? rand(1, $total) : 0;
                $percentSold = $total > 0 ? round(($sold / $total) * 100) : 0;
                return (object) [
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'name' => $product->name,
                    'thumbnail' => $product->thumbnail,
                    'price' => $price,
                    'sale_price' => $sale_price,
                    'discount' => $discount,
                    'categories' => $product->categories,
                    'first_sku' => $firstSku,
                    'rating' => rand(3, 5),
                    'review_count' => rand(5, 100),
                    'sold' => $sold,
                    'total' => $total,
                    'percent_sold' => $percentSold,
                ];
            });
    }


    public function loadMore()
    {
        if ($this->displayLimit < $this->maxLimit) {
            $this->displayLimit += 15;
            if ($this->displayLimit > $this->maxLimit) {
                $this->displayLimit = $this->maxLimit;
            }
        }
    }

    public function collapse()
    {
        $this->displayLimit = 10;
    }

    public function getDisplayedProductsProperty()
    {
        return $this->products->take($this->displayLimit);
    }

    public function render()
    {
        return view('suggestmodule::livewire.suggest', [
            'displayedProducts' => $this->displayedProducts,
            'canLoadMore' => $this->displayLimit < $this->maxLimit,
        ]);
    }
}
