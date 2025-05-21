<?php

namespace Modules\ProductModule\Livewire;

use Livewire\Component;
use App\Models\Product;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;
    public function render()
    {
        $products = Product::with(['productSkus'])
            ->where('product_status', 'active')
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('productmodule::livewire.product-list', [
            'products' => $products,
        ]);
    }
}
