<?php

namespace Modules\HomeModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\RelatedTag;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Category;

class HomeModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */



    public function index()
    {
        $products = Product::with(['categories', 'publisher', 'productSkus'])
            ->where('product_status', 'active')
            ->whereHas('productSkus')
            ->latest()
            ->take(20)
            ->get();


        $relatedTags = RelatedTag::where('related_tag_status', 'active')
            ->with(['products' => function ($query) {
                $query->with('productSkus')
                    ->where('product_status', 'active')
                    ->whereHas('productSkus');
            }])
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

        return view('homemodule::index', compact('products', 'relatedTags', 'wishlistProducts', 'childCategories'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('homemodule::create');
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
        return view('homemodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('homemodule::edit');
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
