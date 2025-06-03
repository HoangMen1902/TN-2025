<?php

namespace Modules\UserModule\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Order as OrderModel;
use Livewire\WithPagination;

class Order extends Component
{
    use WithPagination;

    public $statusFilter = 'all';
    public $search = '';
    public $showCancelModal = false;
    public $orderId;
    public $selectedReason = '';
    public $customReason = '';


    protected $updatesQueryString = ['search', 'statusFilter'];
    protected $paginationTheme = 'tailwind';

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function getOrdersProperty()
    {
        $query = OrderModel::where('user_id', Auth::id());

        if ($this->statusFilter !== 'all') {
            switch ($this->statusFilter) {
                case 'pending-payment':
                    $query->where('orders_status', 'Đang xử lý');
                    break;
                case 'paid':
                    $query->where('orders_status', 'Đã thanh toán');
                    break;
                case 'shipping':
                    $query->where('orders_status', 'Vận chuyển');
                    break;
                case 'completed':
                    $query->where('orders_status', 'Đã giao');
                    break;
                case 'cancelled':
                    $query->where('orders_status', 'Đã hủy');
                    break;
                case 'refund':
                    $query->whereIn('orders_status', ['Chờ hoàn tiền', 'Đã hoàn tiền']);
                    break;
            }
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('orderDetails.sku.product', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return $query->with('orderDetails.sku.product.categories')
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    public function openCancelModal($orderId)
    {
        $this->orderId = $orderId;
        $this->reset(['selectedReason', 'customReason']);
        $this->showCancelModal = true;
    }


    public function cancelOrder()
    {
        $reason = $this->selectedReason === 'Lý do khác'
            ? trim($this->customReason)
            : $this->selectedReason;

        if (!$reason) {
            $this->addError('reason', 'Vui lòng chọn hoặc nhập lý do hủy.');
            return;
        }

        OrderModel::find($this->orderId)?->update([
            'orders_status' => 'Đã hủy',
            'reason' => $reason,
        ]);

        $this->reset(['showCancelModal', 'selectedReason', 'customReason']);
      $this->dispatch('toast', type: 'success', message: 'Đã hủy đơn thành công');
    }

    public function render()
    {
        return view('usermodule::livewire.components.order', [
            'orders' => $this->orders,
        ]);
    }
}
