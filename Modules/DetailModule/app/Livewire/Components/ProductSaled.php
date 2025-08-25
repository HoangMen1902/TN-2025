<?php

namespace Modules\DetailModule\Livewire\Components;

use Livewire\Component;
use App\Models\OrderDetail;

class ProductSaled extends Component
{
    public $data;
    public $type;

    public function render()
    {
        if ($this->type === 'product') {
            // Lấy tất cả sku_id của product
            $skuIds = $this->data->productSkus->pluck('id');
            $sold = OrderDetail::whereIn('sku_id', $skuIds)
                ->whereHas('order', function ($q) {
                    $q->where('orders_status', 'Đã giao');
                })
                ->sum('quantity');
        } else { // combo
            $skuIds = $this->data->productSkus->pluck('id');
            $sold = OrderDetail::whereIn('sku_id', $skuIds)
                ->whereHas('order', function ($q) {
                    $q->where('orders_status', 'Đã giao');
                })
                ->sum('quantity');
        }

        return view('detailmodule::livewire.components.product-saled', [
            'sold' => $sold,
        ]);
    }
}
