<?php
namespace Modules\DetailModule\Livewire\Components;

use Livewire\Component;
use App\Models\Rating;

class ProductRating extends Component
{
    public $data;  
    public $type;  

    public function render()
    {
        if ($this->type === 'product') {
            $ratings = Rating::whereHas('orderDetail.sku', function ($q) {
                $q->where('product_id', $this->data->id);
            })->get();

            $count = $ratings->count();
            $avgRating = $count > 0 ? round($ratings->avg('rating'), 1) : 0;
        } else {
            $avgRating = 0;
            $count = 0;
        }

        return view('detailmodule::livewire.components.product-rating', [
            'avgRating' => $avgRating,
            'count' => $count,
        ]);
    }
}