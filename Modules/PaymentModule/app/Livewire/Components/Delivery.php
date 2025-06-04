<?php

namespace Modules\PaymentModule\Livewire\Components;

use App\Models\CheckoutAddress;
use App\Services\GhnService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class Delivery extends Component
{

    public $fee;
    public $carts;
    public $packageLength;
    public $packageWidth;
    public $packageHeight;
    public $size;

    public $ghnFee;
    public $ghnFrom;
    public $ghnTime;


    public function mount()
    {
        $length = [];
        $width = [];
        $packageHeight = 0;
        $packageWeight = 0;

        foreach ($this->carts as $cart) {
            if (isset($cart->combo_id)) {
                $skus = $cart->combo->productSkus;
                $quantity = $cart->quantity ?? 1;
                foreach ($skus as $sku) {
                    $length[] = $sku->product->length;
                    $width[] = $sku->product->width;
                    $packageHeight += $sku->product->height * $quantity;
                    $packageWeight += $sku->product->weight * $quantity;
                }
            } elseif (isset($cart->sku_id)) {
                $quantity = $cart->quantity ?? 1;
                $length[] = $cart->sku->product->length;
                $width[] = $cart->sku->product->width;
                $packageHeight += $cart->sku->product->height * $quantity;
                $packageWeight += $cart->sku->product->weight * $quantity;
            }
        }

        $this->packageLength = !empty($length) ? max($length) : 0;
        $this->packageWidth = !empty($width) ? max($width) : 0;
        $this->packageHeight = $packageHeight;
        $this->size = [
            'height' => $this->packageHeight,
            'width' => $this->packageWidth,
            'length' => $this->packageLength,
            'weight' => $packageWeight
        ];
    }

    #[On('user_selected_address')]
    public function updateFee($id, GhnService $ghnService)
    {
        if (Auth::id()) {
            $data = CheckoutAddress::find($id);
            if ($data->user_id !== Auth::id()) {
                return;
            } else {
                $fee = $ghnService->getFee($this->size, $data->district_id, $data->ward_id);
                if($fee) {
                $ghnFeeRes = json_decode($fee, true);
                    $this->ghnFee = $ghnFeeRes['data']['total'];
                    $this->getEstimatedTimeGhn($ghnService, $data);
                } else {
                    Log::info('Đã có lỗi xảy ra khi lấy phí ');
                    return;
                }
            }
        }
    }

    public function getEstimatedTimeGhn(GhnService $ghn, $data)
    {
                $ghnResponse = $ghn->getEstimatedTime(1935, "600401");
                $resData = json_decode($ghnResponse->getBody(), true);
                $data = $resData['data'];
                $estimated = $data['leadtime_order']['to_estimate_date'];
                $estimatedFrom = $data['leadtime_order']['from_estimate_date'];
                $fromDate = Carbon::parse($estimatedFrom)->setTimezone('Asia/Ho_Chi_Minh');
                $date = Carbon::parse($estimated)->setTimezone('Asia/Ho_Chi_Minh');
                $this->ghnFrom = ucwords($date->translatedFormat('d/m'));
                $this->ghnTime = ucwords($fromDate->translatedFormat('d/m'));
    }

    public function render()
    {
        return view('paymentmodule::livewire.components.delivery');
    }
}
