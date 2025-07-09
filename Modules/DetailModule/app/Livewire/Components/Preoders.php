<?php

namespace Modules\DetailModule\Livewire\Components;

use Livewire\Component;
use App\Models\Preorder;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class Preoders extends Component
{
    public $product;
    public $preordered = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->preordered = $this->isPreordered();
    }

    public function isPreordered()
    {
        return Preorder::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->whereIn('status', ['pending', 'notified'])
            ->exists();
    }

    public function preorder()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Vui lòng đăng nhập để đặt trước.');
            return redirect()->route('login');
        }

        Preorder::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'status' => 'pending',
        ]);

        $this->preordered = true;
        session()->flash('success', 'Đặt trước sản phẩm thành công!');
    }

    public function cancel()
    {
        Preorder::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->whereIn('status', ['pending', 'notified'])
            ->delete();

        $this->preordered = false;
        session()->flash('success', 'Huỷ đặt trước thành công!');
    }

    public function render()
    {
        return view('detailmodule::livewire.components.preoders');
    }
}
