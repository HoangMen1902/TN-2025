<?php

namespace Modules\UserModule\Livewire\Component;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist as ModelsWishlist;

class Wishlist extends Component
{
    use WithPagination;


    protected $listeners = ['wishlistUpdated' => 'refreshWishlist'];

    public function refreshWishlist()
    {
        $this->loadWishlist();
    }
    public function render()
    {
        $wishLists = Auth::check()
            ? ModelsWishlist::where('user_id', Auth::id())
            ->with(['product.categories', 'product.productSkus'])
            ->paginate(10)
            : collect();

        return view('usermodule::livewire.component.wishlist', [
            'wishLists' => $wishLists,
        ]);
    }

    public function removeFromWishlist($productId)
    {
        $deleted = ModelsWishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        if ($deleted) {
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích!']);
        } else {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Sản phẩm không có trong danh sách yêu thích!']);
        }

        $this->dispatch('wishlistUpdated');
    }
}
