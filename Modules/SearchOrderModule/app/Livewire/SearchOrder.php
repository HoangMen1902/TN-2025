<?php

namespace Modules\SearchOrderModule\Livewire;

use Livewire\Component;
use App\Services\GhnService;
use App\Services\ViettelPostService;
use App\Models\PaymentDetail;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class SearchOrder extends Component
{
    public $tracking_id = '';
    public $result = null;
    public $error = '';

    public function search()
    {
        $this->error = '';
        $this->result = null;

        // Tìm đơn theo tracking_id
        $order = Order::where('shipping_order_code', $this->tracking_id)->first();

        if (!$order) {
            $this->dispatch('toast', type: 'error', message: 'Không tìm thấy đơn hàng với mã này!');
            Log::error('Tra cứu đơn hàng thất bại: Không tìm thấy đơn với mã shipping_order_code = ' . $this->tracking_id);
            $this->error = 'Không tìm thấy đơn hàng với mã này!';
            return;
        }

        // Lấy đơn vị vận chuyển từ paymentDetail
        $paymentDetail = $order->paymentDetail;
        $shipment_unit = $paymentDetail->shipment_unit ?? '';

        if (!$shipment_unit) {
            $this->dispatch('toast', type: 'error', message: 'Không xác định được đơn vị vận chuyển!');
            Log::error('Tra cứu đơn hàng thất bại: Không xác định shipment_unit cho order_id = ' . $order->id);
            $this->error = 'Không xác định được đơn vị vận chuyển!';
            return;
        }

        if ($shipment_unit === 'Giao hàng nhanh') {
            $ghn = new GhnService();
            $apiResult = $ghn->trackOrder($this->tracking_id);
            $this->result = [
                'unit' => 'GHN',
                'data' => $apiResult,
            ];
        } elseif ($shipment_unit === 'Viettel Post') {
            $viettel = new ViettelPostService();
            $apiResult = $viettel->trackOrder($this->tracking_id);
            $this->result = [
                'unit' => 'ViettelPost',
                'data' => $apiResult,
            ];
        } else {
            $this->error = 'Không xác định được đơn vị vận chuyển!';
            $this->dispatch('toast', type: 'error', message: 'Không xác định được đơn vị vận chuyển!');
        }
    }

    public function render()
    {
        return view('searchordermodule::livewire.search-order');
    }
}
