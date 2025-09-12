<?php

namespace Modules\ProductModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Category;
use App\Models\Product;
use App\Models\RelatedTag;
use Illuminate\Support\Facades\Auth;
class ProductModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $products = Product::with([
            'publisher',
            'productSkus',
            'categories' => function ($query) {
                $query->limit(3);
            }
        ])
        ->where('product_status', 'active')
        ->whereHas('productSkus')
        ->latest()
        ->take(20)
        ->get();



       

        $wishlistProducts = collect();

        if (Auth::check()) {
            $wishlistProducts = Wishlist::with('product.productSkus', 'product.categories')
                ->where('user_id', Auth::id())
                ->get()
                ->pluck('product')
                ->filter();
        }
        $childCategories = Category::active()
            ->whereNotNull('parent_id')
            ->get();

        return view('productmodule::index', compact('products', 'wishlistProducts', 'childCategories'));
       
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productmodule::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('productmodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('productmodule::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
