<?php

namespace Modules\SuggestModule\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\SearchHistory;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Modules\EbookModule\Http\Controllers\EbookModuleController;

// use Illuminate\Support\Str;

class Suggest extends Component
{
    public $products;
    public $displayLimit = 10;
    public $maxLimit = 70;
    public $isMobile = false;

    protected $listeners = ['load-suggest' => 'setDevice'];

    public function mount($isMobile = false)
    {
        $this->isMobile = $isMobile;
        $this->loadProducts();
    }

    public function setDevice($data)
    {
        $this->isMobile = $data['isMobile'] ?? false;
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
        $cartSkuIds = [];
        if ($userId) {
            $cartSkuIds = \App\Models\Cart::where('user_id', $userId)
                ->pluck('sku_id')
                ->unique()
                ->toArray();
        }
        // else {
        //     $sessionId = session()->getId();
        //     $cartSkuIds = \App\Models\Cart::where('session_id', $sessionId)
        //         ->pluck('sku_id')
        //         ->unique()
        //         ->toArray();
        // }

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
            })->pluck('sku_id')->unique()->toArray();

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

        $priorityCategoryIds = [];
        if ($products->count() > 0) {
            $priorityCategoryIds = $products->pluck('categories')->flatten()->pluck('id')->unique()->toArray();
        }

        if (!empty($keywords) && $products->count() < $this->maxLimit) {
            $keywordProducts = Product::with(['productSkus', 'categories'])
                ->whereHas('productSkus')
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $kw) {
                        $q->orWhere('name', 'like', "%$kw%");
                    }
                })
                ->when(!empty($priorityCategoryIds), function ($q) use ($priorityCategoryIds) {
                    $q->whereHas('categories', function ($q2) use ($priorityCategoryIds) {
                        $q2->whereIn('categories.id', $priorityCategoryIds);
                    });
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
                ->when(!empty($priorityCategoryIds), function ($q) use ($priorityCategoryIds) {
                    $q->whereHas('categories', function ($q2) use ($priorityCategoryIds) {
                        $q2->whereIn('categories.id', $priorityCategoryIds);
                    });
                })
                ->withCount(['productSkus as sold_count' => function (Builder $query) {
                    $query->join('order_details', 'product_skus.id', '=', 'order_details.sku_id');
                }])
                ->orderByDesc('sold_count')
                ->inRandomOrder()
                ->limit($this->maxLimit - $products->count())
                ->get();

            $products = $products->concat($randomProducts);
        }

        if ($products->count() < $this->maxLimit) {
            $moreProducts = Product::with(['productSkus', 'categories'])
                ->whereHas('productSkus')
                ->whereNotIn('id', $products->pluck('id')->toArray())
                ->withCount(['productSkus as sold_count' => function (Builder $query) {
                    $query->join('order_details', 'product_skus.id', '=', 'order_details.sku_id');
                }])
                ->orderByDesc('sold_count')
                ->limit($this->maxLimit - $products->count())
                ->get();

            $products = $products->concat($moreProducts);
        }

        $this->products = $products->map(function ($product) {
            $firstSku = $product->productSkus->first();
            $price = $firstSku?->price ?? 0;
            $sale_price = $firstSku?->sale_price ?? $price;
            $discount = ($price > $sale_price && $price > 0)
                ? round((($price - $sale_price) / $price) * 100)
                : 0;
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
                'rating' => rand(3, 5),
                'review_count' => rand(5, 100),
                'sold' => $sold,
                'total' => $total,
                'percent_sold' => $percentSold,
                'is_ebook' => false, 
            ];
        });
        // hiện ebook ở trang chủ
        $ebooks = EbookModuleController::mapEbookToProductFormat()->take(5);
        $this->products = $this->products->concat($ebooks)->take($this->maxLimit);
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
        $firstLimit = $this->isMobile ? 6 : 5;

        return $this->products
            ->take($this->displayLimit)
            ->map(function ($product, $index) use ($firstLimit) {
                $product->inFirstSection = $index < $firstLimit;
                return $product;
            });
    }

    public function render()
    {
        return view('suggestmodule::livewire.suggest', [
            'displayedProducts' => $this->displayedProducts,
            'canLoadMore' => $this->displayLimit < $this->maxLimit,
        ]);
    }
}
