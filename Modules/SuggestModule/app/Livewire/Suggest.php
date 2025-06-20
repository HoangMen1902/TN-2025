<?php

namespace Modules\SuggestModule\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderDetail;

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
        $userId = Auth::id();
        $keywords = [];

        if ($userId) {
            $keywords = SearchHistory::where('user_id', $userId)
                ->orderByDesc('updated_at')
                ->limit(5)
                ->pluck('keyword')
                ->toArray();
        }


        $cartProductIds = [];
        if ($userId) {
            $cartSkuIds = \App\Models\Cart::where('user_id', $userId)
                ->pluck('sku_id')
                ->unique()
                ->toArray();
        } else {
            $sessionId = session()->getId();
            $cartSkuIds = \App\Models\Cart::where('session_id', $sessionId)
                ->pluck('sku_id')
                ->unique()
                ->toArray();
        }

        if (!empty($cartSkuIds)) {
            $cartProductIds = \App\Models\ProductSku::whereIn('id', $cartSkuIds)
                ->pluck('product_id')
                ->unique()
                ->toArray();
        }


        $purchasedProductIds = [];
        if ($userId) {
            $skuIds = OrderDetail::whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->where('orders_status', '!=', 'Đã hủy');
            })
                ->pluck('sku_id')
                ->unique()
                ->toArray();

            if (!empty($skuIds)) {
                $purchasedProductIds = \App\Models\ProductSku::whereIn('id', $skuIds)
                    ->pluck('product_id')
                    ->unique()
                    ->toArray();
            }
        }

        $products = collect();

        if (!empty($purchasedProductIds)) {
            $products = Product::with(['productSkus', 'categories'])
                ->whereHas('productSkus')
                ->whereIn('id', $purchasedProductIds)
                ->whereNotIn('id', $cartProductIds)
                ->limit($this->maxLimit)
                ->get();
        }

        if (!empty($keywords) && $products->count() < $this->maxLimit) {
            $keywordProducts = Product::with(['productSkus', 'categories'])
                ->whereHas('productSkus')
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $kw) {
                        $q->orWhere('name', 'like', '%' . $kw . '%');
                    }
                })
                ->whereNotIn('id', array_merge($products->pluck('id')->toArray(), $cartProductIds))
                ->limit($this->maxLimit - $products->count())
                ->get();

            $products = $products->concat($keywordProducts);
        }


        if ($products->count() < $this->maxLimit) {
            $excludeIds = array_merge($products->pluck('id')->toArray(), $cartProductIds);
            $randomProducts = Product::with(['productSkus', 'categories'])
                ->whereHas('productSkus')
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->limit($this->maxLimit - $products->count())
                ->get();

            $products = $products->concat($randomProducts);
        }

        $this->products = $products->map(function ($product) {
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
