<?php
namespace Modules\UserModule\app\Http\Livewire\Component;

use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ExportOrderPdf extends Component
{
    public $order;
    public $pdfUrl;

    public function exportPdf()
    {
        $order = [
            'id' => 1,
            'customer_name' => 'Nguyễn Hoài Bão',
            'created_at' => now(),
            'total' => 26000,
            'items' => [
                ['product_name' => 'Sách "Cách để bớt ngu"', 'quantity' => 2, 'price' => 9000],
                ['product_name' => 'Tập Vở 200 Trang', 'quantity' => 1, 'price' => 8000],
            ],
        ];

        $pdf = Pdf::loadView('usermodule::profile.PDFOrder', ['order' => $order]);
        $pdfPath = 'orders/order_' . $order['id'] . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        $this->pdfUrl = Storage::url($pdfPath);
    }

    public function render()
    {
        return view('usermodule::livewire.component.export-order-pdf');
    }
}
