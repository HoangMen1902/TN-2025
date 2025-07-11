<?php

namespace Modules\UserModule\Livewire\Components;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Order as OrderModel;
use App\Models\Rating;
use Livewire\WithFileUploads;
use App\Models\User;

class Order extends Component
{
    use WithPagination, WithFileUploads;

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
    public $currentDetailId = null;
    public $showRatingModal = false;
    public $selectedOrderId = null;
    public $ratings = [];
    public $comments = [];
    public $rating = 0;
    public $images = [];
    public $anonymous = [];
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
        activity()
            ->causedBy(Auth::user())
            ->performedOn(OrderModel::find($this->orderId))
            ->withProperties(['role' => Auth::user()?->role ?? 'client'])
            ->log('Người dùng hủy đơn hàng: ' . $this->orderId);

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

    public function openRatingModal($orderId)
    {
        $this->selectedOrderId = $orderId;
        $this->showRatingModal = true;

        $order = OrderModel::with('orderDetails.sku.product')->find($orderId);
        foreach ($order->orderDetails as $detail) {
            $this->ratings[$detail->id] = 5;
            $this->comments[$detail->id] = '';
        }
    }


    public function submitRatings()
    {
        if (!Auth::check()) {
            $this->dispatch('toast', type: 'error', message: 'Bạn cần đăng nhập để đánh giá!');
            return;
        }

        $order = OrderModel::with('orderDetails.sku.product')->find($this->selectedOrderId);

        if (!$order || $order->orders_status !== 'Đã giao') {
            $this->dispatch('toast', type: 'error', message: 'Chỉ đánh giá được đơn hàng đã giao!');
            return;
        }

        // Kiểm tra nếu có sản phẩm đã đánh giá thì báo lỗi và dừng lại
        foreach ($order->orderDetails as $detail) {
            $exists = Rating::where('user_id', Auth::id())
                ->where('order_detail_id', $detail->id)
                ->exists();
            if ($exists) {
                $this->dispatch('toast', type: 'error', message: 'Bạn đã đánh giá sản phẩm này rồi!');
                return;
            }
        }

        // Validate tất cả sản phẩm
        foreach ($order->orderDetails as $detail) {
            $this->validate([
                "ratings.{$detail->id}" => 'required|integer|min:1|max:5',
                "comments.{$detail->id}" => 'required|string|min:10',
            ], [
                "ratings.{$detail->id}.required" => 'Vui lòng chọn số sao.',
                "comments.{$detail->id}.required" => 'Vui lòng nhập nhận xét.',
                "comments.{$detail->id}.min" => 'Nhận xét tối thiểu 10 ký tự.',
            ]);
        }

        // Lưu đánh giá
        foreach ($order->orderDetails as $detail) {
            $rating = $this->ratings[$detail->id] ?? null;
            $comment = $this->comments[$detail->id] ?? '';
            $isAnonymous = $this->anonymous[$detail->id] ?? false;
            $imagePaths = [];
            if (!empty($this->images[$detail->id])) {
                foreach ($this->images[$detail->id] as $img) {
                    $imagePaths[] = $img->store('ratings', 'public');
                }
            }
            Rating::create([
                'user_id' => Auth::id(),
                'order_detail_id' => $detail->id,
                'review' => $comment,
                'rating' => $rating,
                'status' => 'active',
                'is_anonymous' => $isAnonymous,
                'images' => json_encode($imagePaths),
            ]);
        }

        $this->showRatingModal = false;

        $this->rewardPointsForRating($order);
    }
    public function removeImage($detailId, $index)
    {
        if (isset($this->images[$detailId][$index])) {
            unset($this->images[$detailId][$index]);
            $this->images[$detailId] = array_values($this->images[$detailId]);
        }
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

    // Tính điểm thưởng cho người dùng khi đánh giá đơn hàng
    protected function rewardPointsForRating($order)
    {
        $earnedPoints = floor($order->calculated_total_price / 1000);

        if ($earnedPoints > 0) {
            $user = User::find(Auth::id());

            $userPoint = $user->point()->firstOrCreate(['user_id' => $user->id], [
                'total_points' => 0,
                'redeemable_points' => 0,
            ]);

            // Cộng điểm vào 2 cột
            $userPoint->increment('total_points', $earnedPoints);
            $userPoint->increment('redeemable_points', $earnedPoints);

            // Ghi log giao dịch điểm
            $user->pointTransactions()->create([
                'points' => $earnedPoints,
                'type' => 'earn',
                'source' => 'Cộng điểm từ đánh giá đơn hàng #' . $order->id,
            ]);
            
            $this->dispatch('toast', type: 'success', message: 'Đánh giá thành công! bạn đã nhận được ' . $earnedPoints . ' điểm hội viên!');
        }
    }


    public function render()
    {
        return view('usermodule::livewire.components.order', [
            'orders' => $this->orders,
        ]);
    }
}
