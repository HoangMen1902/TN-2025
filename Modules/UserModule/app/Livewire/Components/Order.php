<?php

namespace Modules\UserModule\Livewire\Components;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Order as OrderModel;
use App\Models\Rating;

class Order extends Component
{
    use WithPagination;

    // Filtering & Searching
    public $statusFilter = 'all';
    public $search = '';

    protected $updatesQueryString = ['search', 'statusFilter'];
    protected $paginationTheme = 'tailwind';

    // Cancel Modal
    public $showCancelModal = false;
    public $orderId;
    public $selectedReason = '';
    public $customReason = '';

    // Rating Modal
    public $showRatingModal = false;
    public $selectedOrderId = null;
    public $ratings = [];
    public $comments = [];
    public $rating = 0;

    // =====================
    // FILTER + SEARCH
    // =====================

    public function setStatusFilter($status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    // =====================
    // CANCEL ORDER
    // =====================

    public function openCancelModal($orderId): void
    {
        $this->orderId = $orderId;
        $this->reset(['selectedReason', 'customReason']);
        $this->showCancelModal = true;
    }

    public function cancelOrder(): void
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

        $this->reset(['showCancelModal', 'selectedReason', 'customReason', 'orderId']);
        $this->dispatch('toast', type: 'success', message: 'Đã hủy đơn thành công');
    }

    // =====================
    // RATING
    // =====================

    public function setRating($value)
    {
        $this->rating = $value;
    }

    public function openRatingModal($orderId): void
    {
        $this->selectedOrderId = $orderId;
        $this->resetValidation();
        $this->showRatingModal = true;
    }

    public function submitRatings(): void
    {
        $order = OrderModel::with('orderDetails.sku.product')->find($this->selectedOrderId);

        if (!$order) return;

        foreach ($order->orderDetails as $detail) {
            $rating = $this->ratings[$detail->id] ?? null;
            $comment = $this->comments[$detail->id] ?? '';

            if ($rating) {
                Rating::create([
                    'user_id' => Auth::id(),
                    'product_id' => $detail->sku->product->id,
                    'rating' => $rating,
                    'comment' => $comment,
                    'order_id' => $order->id,
                ]);
            }
        }

        $this->reset(['ratings', 'comments', 'selectedOrderId', 'showRatingModal']);
        $this->dispatch('toast', type: 'success', message: 'Đã gửi đánh giá!');
    }

    // =====================
    // ORDERS PROPERTY
    // =====================

    public function getOrdersProperty()
    {
        $query = OrderModel::where('user_id', Auth::id());

        if ($this->statusFilter !== 'all') {
            $query->where(function ($q) {
                switch ($this->statusFilter) {
                    case 'pending-payment':
                        $q->where('orders_status', 'Đang xử lý');
                        break;
                    case 'paid':
                        $q->where('orders_status', 'Đã thanh toán');
                        break;
                    case 'shipping':
                        $q->where('orders_status', 'Vận chuyển');
                        break;
                    case 'completed':
                        $q->where('orders_status', 'Đã giao');
                        break;
                    case 'cancelled':
                        $q->where('orders_status', 'Đã hủy');
                        break;
                    case 'refund':
                        $q->whereIn('orders_status', ['Chờ hoàn tiền', 'Đã hoàn tiền']);
                        break;
                }
            });
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('orderDetails.sku.product', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        return $query
            ->with('orderDetails.sku.product.categories')
            ->orderByDesc('created_at')
            ->paginate(5);
    }

    // =====================
    // RENDER
    // =====================

    public function render()
    {
        return view('usermodule::livewire.components.order', [
            'orders' => $this->orders,
        ]);
    }
}
