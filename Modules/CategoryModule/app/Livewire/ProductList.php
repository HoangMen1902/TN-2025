<?php

namespace Modules\CategoryModule\Livewire;

use Livewire\WithPagination;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSku;
use App\Models\Publisher;
use App\Models\RelatedTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductList extends Component
{
    use WithPagination;
    public $openCategories = []; // Lưu trạng thái mở/tắt từng danh mục cha
    public $showMoreCategories = false;
    public $showMorePublishers = false;
    public $showMoreTags = false;

    // Nếu muốn lưu trạng thái "xem thêm" riêng cho từng danh mục cha, dùng mảng:
    public $showMoreChildren = [];
    public $selectedCategoryIds = [];
    public $selectedPublisherIds = [];
    public $selectedTagIds = [];
    public $sortOrder = 'desc';
    public $perPage = 9;
    public $minPrice = 0;
    public $maxPrice = 100000000;
    public $categorySlug;
    public $categoryId;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedPublisherIds',
        'selectedCategoryIds',
        'selectedTagIds',
        'minPrice',
        'maxPrice',
    ];
    public function toggleCategory($categoryId)
    {
        $this->openCategories[$categoryId] = !($this->openCategories[$categoryId] ?? false);
    }

    public function toggleShowMore($type, $id = null)
    {
        if ($type === 'category') {
            $this->showMoreCategories = !$this->showMoreCategories;
        } elseif ($type === 'publisher') {
            $this->showMorePublishers = !$this->showMorePublishers;
        } elseif ($type === 'tag') {
            $this->showMoreTags = !$this->showMoreTags;
        } elseif ($type === 'children' && $id) {
            $this->showMoreChildren[$id] = !($this->showMoreChildren[$id] ?? false);
        }
    }


    public function mount($categorySlug)
    {
        $this->categorySlug = $categorySlug;
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $this->categoryId = $category->id;
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
        $category = Category::find($this->categoryId);

        $query = Product::with(['productSkus', 'categories', 'publisher', 'tags'])
            ->where('product_status', 'active')
            ->whereHas('categories', function ($q) {
                $q->where('categories.id', $this->categoryId);
            });

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

        return view('categorymodule::livewire.product-list', [
            'products' => $query->paginate($this->perPage),
            'category' => $category,
            'publishers' => Publisher::where('publisher_status', 'active')->get(),
            'tags' => RelatedTag::where('related_tag_status', 'active')->get(),
        ]);
    }
}
