<?php

namespace Modules\UserModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['product.categories', 'product.productSkus'])
            ->paginate(10);

        return view('usermodule::profile.wishlist', compact('wishlists'));
    }

    public function store(Request $request)
    {
        $productId = $request->input('product_id');
        $userId = Auth::id();

        // Kiểm tra sản phẩm có tồn tại
        $product = Product::findOrFail($productId);

        
        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($exists) {
            
            Wishlist::where('user_id', $userId)
                ->where('product_id', $productId)
                ->delete();
            return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích!');
        }

       
        Wishlist::create([
            'product_id' => $productId,
            'user_id' => $userId,
        ]);

        return redirect()->back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích!');
    }

    public function destroy($product_id)
    {
        
        $deleted = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        if (!$deleted) {
            return redirect()->back()->with('error', 'Sản phẩm không có trong danh sách yêu thích!');
        }

        return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi danh sách yêu thích!');
    }
}