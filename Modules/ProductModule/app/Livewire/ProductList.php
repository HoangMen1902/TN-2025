<?php

namespace Modules\ProductModule\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSku;
use App\Models\Publisher;
use App\Models\RelatedTag;
use Illuminate\Support\Facades\DB;

class ProductList extends Component
{
    use WithPagination;

    public $selectedCategoryIds = [];
    public $selectedParentCategoryIds = [];
    public $selectedPublisherIds = [];
    public $selectedTagIds = [];
    public $sortOrder = 'desc';
    public $perPage = 9;
    public $minPrice = 0;
    public $maxPrice = 100000000;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedPublisherIds',
        'selectedCategoryIds',
        'selectedTagIds',
        'minPrice',
        'maxPrice',
    ];

    public function mount(Request $request)
    {
        $this->search = $request->query('search', '');
    }

    public function updated($propertyName)
    {
        $this->resetPage();
    }
    public function setPriceRange($min, $max)
    {
        $this->minPrice = $min;
        $this->maxPrice = $max;
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::with(['productSkus', 'categories', 'publisher', 'tags'])
            ->where('product_status', 'active');

        $allCategoryIds = [];

        if (!empty($this->selectedParentCategoryIds)) {
            $childCategoryIds = Category::whereIn('parent_id', $this->selectedParentCategoryIds)->pluck('id')->toArray();
            $allCategoryIds = array_merge($allCategoryIds, $this->selectedParentCategoryIds, $childCategoryIds);
        }

        if (!empty($this->selectedCategoryIds)) {
            $allCategoryIds = array_merge($allCategoryIds, $this->selectedCategoryIds);
        }

        if (!empty($allCategoryIds)) {
            $allCategoryIds = array_unique($allCategoryIds);
            $query->whereHas('categories', function ($q) use ($allCategoryIds) {
                $q->whereIn('categories.id', $allCategoryIds);
            });
        }

        if (!empty($this->selectedPublisherIds)) {
            $query->whereIn('publisher_id', $this->selectedPublisherIds);
        }

        if (!empty($this->selectedTagIds)) {
            $query->whereHas('tags', function ($q) {
                $q->whereIn('related_tags.id', $this->selectedTagIds);
            });
        }

        $query->whereHas('productSkus', function ($q) {
            $q->where('id', function ($sub) {
                $sub->select('id')
                    ->from('product_skus')
                    ->whereColumn('product_id', 'products.id')
                    ->orderBy('id')
                    ->limit(1);
            })->whereBetween('sale_price', [$this->minPrice, $this->maxPrice]);
        });


        switch ($this->sortOrder) {
            case 'desc':
                $query->orderBy('published_at', 'desc');
                break;
            case 'asc':
                $query->orderBy('published_at', 'asc');
                break;
            case 'ban-chay-thang':
                $query->addSelect(['monthly_sales' => function ($q) {
                    $q->selectRaw('SUM(order_details.quantity)')
                        ->from('order_details')
                        ->join('orders', 'orders.id', '=', 'order_details.order_id')
                        ->join('product_skus', 'product_skus.id', '=', 'order_details.sku_id')
                        ->whereColumn('product_skus.product_id', 'products.id')
                        ->where('order_details.created_at', '>=', now()->subMonth());
                }])->orderByDesc('monthly_sales');
                break;
            case 'chiet-khau':
                $query->addSelect([
                    'max_discount' => DB::table('product_skus')
                        ->selectRaw('MAX(price - sale_price)')
                        ->whereColumn('product_skus.product_id', 'products.id')
                        ->whereNotNull('sale_price')
                        ->whereColumn('price', '>', 'sale_price')
                ])->orderByDesc('max_discount');
                break;
            case 'gia-giam-asc':
                $query->withMin('productSkus', 'sale_price')->orderBy('product_skus_min_sale_price', 'asc');
                break;
            case 'gia-giam-desc':
                $query->withMin('productSkus', 'sale_price')->orderBy('product_skus_min_sale_price', 'desc');
                break;
            case 'gia-ban-asc':
                $query->withMin('productSkus', 'price')->orderBy('product_skus_min_price', 'asc');
                break;
            case 'gia-ban-desc':
                $query->withMin('productSkus', 'price')->orderBy('product_skus_min_price', 'desc');
                break;
        }


        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        return view('productmodule::livewire.product-list', [
            'products' => $query->paginate($this->perPage),
            'categories' => Category::with('children')->whereNull('parent_id')->get(),
            'publishers' => Publisher::where('publisher_status', 'active')->get(),
            'tags' => RelatedTag::where('related_tag_status', 'active')->get(),
        ]);
    }
}
