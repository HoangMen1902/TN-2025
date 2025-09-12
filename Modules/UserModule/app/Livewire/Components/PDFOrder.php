<?php

namespace Modules\UserModule\Http\Livewire\Components;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;

class PDFOrder extends Component
{
    public $orderId;

    public function mount($orderId)
    {
        $this->orderId = $orderId;
    }

    public function exportPdf()
    {
        $order = Order::with('orderDetails.sku.product')
            ->where('id', $this->orderId)
            ->firstOrFail();

        $items = $order->orderDetails->map(function ($detail) {
            $productName = $detail->sku->product->name ?? 'Sản phẩm không tên';

            $price = $detail->price;
            $salePrice = $detail->sale_price ?? $price;
            $quantity = $detail->quantity;

            return [
                'product_name' => $productName,
                'quantity' => $quantity,
                'price' => $price,
                'discount' => ($price - $salePrice) * $quantity,
                'total_price' => $salePrice * $quantity,
            ];
        })->toArray();

        $total = $order->orderDetails->sum(function ($item) {
            $salePrice = $item->sale_price ?? $item->price;
            return $salePrice * $item->quantity;
        });

        $orderData = [
            'id' => $order->id,
            'customer_name' => $order->customer_name,
            'created_at' => $order->created_at,
            'total' => $total,
            'payment_method' => $order->payment_method ?? 'Tiền mặt',
            'customer_phone' => $order->phone ?? 'Chưa có SĐT',
            'customer_address' => $order->address ?? 'Chưa có địa chỉ',
            'items' => $items,
        ];

        $pdf = Pdf::loadView('usermodule::pdf.invoice', ['order' => $orderData]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'hoa-don-' . $order->id . '.pdf');
    }

    public function render()
    {
        return view('usermodule::livewire.components.pdf-order');
    }
}
