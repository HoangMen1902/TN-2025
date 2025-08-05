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
    private function beautifyStatus($status)
    {
        if (!$status) return '';

        $status = str_replace('_', ' ', $status);
        $status = mb_convert_case($status, MB_CASE_TITLE, "UTF-8");

        $map = [
            'Ready To Pick' => 'Chờ lấy hàng',
            'Delivering' => 'Đang giao hàng',
            'Delivered' => 'Đã giao hàng',
            'Cancelled' => 'Đã hủy',

        ];
        return $map[$status] ?? $status;
    }
    private function beautifyDatetime($datetime)
    {
        if (!$datetime) return '';
        try {
            $dt = new \DateTime($datetime);
            return $dt->format('d/m/Y H:i');
        } catch (\Exception $e) {
            return $datetime;
        }
    }
    public function search()
    {
        $this->error = '';
        $this->result = null;

        // Tìm đơn theo tracking_id
        $payment = PaymentDetail::where('tracking_id', $this->tracking_id)->first();

        if(!$payment) {
            $this->dispatch('toast', type: 'error', message: 'Không tìm thấy đơn hàng với mã này!');
            Log::error('Tra cứu đơn hàng thất bại: Không tìm thấy đơn với mã shipping_order_code = ' . $this->tracking_id);
            $this->error = 'Không tìm thấy đơn hàng với mã này!';
            return;
        }

        $order = $payment->order->shipping_order_code;

        if (!$order) {
            $this->dispatch('toast', type: 'error', message: 'Không tìm thấy đơn hàng với mã này!');
            Log::error('Tra cứu đơn hàng thất bại: Không tìm thấy đơn với mã shipping_order_code = ' . $this->tracking_id);
            $this->error = 'Không tìm thấy đơn hàng với mã này!';
            return;
        }

        // Lấy đơn vị vận chuyển từ paymentDetail
        $paymentDetail = $payment;
        $shipment_unit = strtolower($paymentDetail->shipment_unit ?? '');

        if (!$shipment_unit) {
            $this->dispatch('toast', type: 'error', message: 'Không xác định được đơn vị vận chuyển!');
            Log::error('Tra cứu đơn hàng thất bại: Không xác định shipment_unit cho order_id = ' . $order->id);
            $this->error = 'Không xác định được đơn vị vận chuyển! 1';
            return;
        }

        if ($shipment_unit === 'giao hàng nhanh') {
            $ghn = new GhnService();
            $apiResult = $ghn->trackOrder($order);


            $data = $apiResult['data'] ?? [];
            $this->result = [
                'unit' => 'GHN',
                'data' => [
                    'order_code'      => $data['order_code'] ?? '',
                    'pick_date'    => $this->beautifyDatetime($data['pickup_time'] ?? ''),
                    'deliver_date' => $this->beautifyDatetime($data['leadtime'] ?? ''),
                    'status' => $this->beautifyStatus($data['status'] ?? ''),
                    'sender_name'     => $data['from_name'] ?? '',
                    'sender_phone'    => $data['from_phone'] ?? '',
                    'sender_address'  => $data['from_address'] ?? '',
                    'receiver_name'   => $data['to_name'] ?? '',
                    'receiver_phone'  => $data['to_phone'] ?? '',
                    'receiver_address' => $data['to_address'] ?? '',
                    'history'         => [],
                ],
            ];
        } elseif ($shipment_unit === 'viettel post') {
            $viettel = new ViettelPostService();
            $apiResult = $viettel->trackOrder($this->tracking_id);

            $data = $apiResult['data'] ?? $apiResult;
            $this->result = [
                'unit' => 'ViettelPost',
                'data' => [
                    'order_code'      => $data['ORDER_NUMBER'] ?? '',
                    'pick_date'    => $this->beautifyDatetime($data['ORDER_DATE'] ?? ''),
                    'deliver_date' => $this->beautifyDatetime($data['DELIVERY_DATE'] ?? ''),
                    'status' => $this->beautifyStatus($data['ORDER_STATUS'] ?? ''),
                    'sender_name'     => $data['SENDER_FULLNAME'] ?? '',
                    'sender_phone'    => $data['SENDER_PHONE'] ?? '',
                    'sender_address'  => $data['SENDER_ADDRESS'] ?? '',
                    'receiver_name'   => $data['RECEIVER_FULLNAME'] ?? '',
                    'receiver_phone'  => $data['RECEIVER_PHONE'] ?? '',
                    'receiver_address' => $data['RECEIVER_ADDRESS'] ?? '',
                    'history'         => [],
                ],
            ];
        } else {
            $this->error = 'Không xác định được đơn vị vận chuyển! 2' . $shipment_unit;
            $this->dispatch('toast', type: 'error', message: 'Không xác định được đơn vị vận chuyển!');
        }
    }

    public function render()
    {
        return view('searchordermodule::livewire.search-order');
    }
}
