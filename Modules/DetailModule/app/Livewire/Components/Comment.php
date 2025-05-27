<?php
namespace Modules\DetailModule\Livewire\Components;

use App\Models\Rating;
use Livewire\Component;

class Comment extends Component
{
    public $id;  

    public function mount($id)
    {
        $this->id = $id;
    }

    public function render()
    {
        $ratings = Rating::whereHas('orderDetail.sku', function ($query) {
            $query->where('product_id', $this->id);
        })->with(['user', 'orderDetail.sku'])->get();

        return view('detailmodule::livewire.components.comment', [
            'ratings' => $ratings,
        ]);
    }
}

