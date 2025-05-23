<?php

namespace Modules\DetailModule\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist as ModelsWishlist;

class WishList extends Component
{
     public $data;

     public $isInWishlist = false;

    public function mount($data)
    {
        $this->isInWishlist = Auth::check() && ModelsWishlist::where('user_id', Auth::id())
            ->where('product_id', $data->id)
            ->exists();
    }

    public function toggleWishlist()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $productId = $this->data->id;

        $wishlist = ModelsWishlist::where('user_id', $userId)->where('product_id', $productId);

        if ($wishlist->exists()) {
            $wishlist->delete();
            $this->isInWishlist = false;
        } else {
            ModelsWishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $this->isInWishlist = true;
        }

       
      $this->dispatch('wishlistUpdated');

    }

    public function render()
    {
        return view('detailmodule::livewire.components.wish-list');
    }
}
