<?php

namespace Modules\SuggestModule\Livewire;

use App\Models\Product;
// use App\Models\ProductEbook;
use Livewire\Component;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderDetail;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Str;

class Suggest extends Component
{
    public $products;
    // public $displayedProducts;
    public $displayLimit = 10;
    public $maxLimit = 70;
    public $showAll = false;

    public function mount()
    {
        $this->loadProducts();
        // // Lấy tất cả eBooks từ bảng ebooks
        // $ebooks = ProductEbook::all(); // Điều chỉnh namespace model nếu cần

        // // Biến đổi dữ liệu eBooks để khớp với cấu trúc template
        // $this->displayedProducts = $ebooks->map(function ($ebook) {
        //     return (object) [
        //         'name' => $ebook->title, // Ánh xạ title thành name
        //         'slug' => Str::slug($ebook->title), // Tạo slug từ title
        //         'thumbnail' => $ebook->cover_image, // Ánh xạ cover_image thành thumbnail
        //         'sale_price' => $ebook->price, // Giả định sale_price là price
        //         'price' => $ebook->price, // Giả định price ban đầu bằng sale_price
        //         'discount' => 0, // Giả định không có discount, có thể điều chỉnh
        //         'percent_sold' => 0, // Giả định percent_sold ban đầu là 0, có thể điều chỉnh
        //         'is_ebook' => true, // Đánh dấu là eBook
        //         'file_format' => pathinfo($ebook->file_path, PATHINFO_EXTENSION) ?? 'PDF', // Lấy định dạng từ file_path
        //     ];
        // })->take($this->displayLimit); // Giới hạn số lượng dựa trên displayLimit
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

        // Lấy sản phẩm đã mua
        if (!empty($purchasedProductIds)) {
            $products = Product::with(['productSkus', 'categories'])
                ->whereHas('productSkus')
                ->whereIn('id', $purchasedProductIds)
                ->whereNotIn('id', $cartProductIds)
                ->limit($this->maxLimit)
                ->get();
        }

        // Lấy category từ sản phẩm đã mua hoặc theo từ khóa
        $priorityCategoryIds = [];
        if ($products->count() > 0) {
            $priorityCategoryIds = $products->pluck('categories')->flatten()->pluck('id')->unique()->toArray();
        }

        // Ưu tiên sản phẩm theo từ khóa, cùng danh mục
        if (!empty($keywords) && $products->count() < $this->maxLimit) {
            $keywordProducts = Product::with(['productSkus', 'categories'])
                ->whereHas('productSkus')
                ->where(function ($q) use ($keywords) {
                    foreach ($keywords as $kw) {
                        $q->orWhere('name', 'like', '%' . $kw . '%');
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

        // Ưu tiên random sản phẩm cùng danh mục, bán chạy
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

        // Nếu vẫn thiếu, lấy sản phẩm bán chạy nhất toàn shop
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
