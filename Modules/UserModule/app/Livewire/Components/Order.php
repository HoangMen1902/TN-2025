<?php

namespace Modules\UserModule\Livewire\Components;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Order as OrderModel;
use App\Models\Rating;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentDetail;
use App\Services\VietQRService;
use Illuminate\Support\Facades\DB;

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
    // refund
    public $showRefundModal = false;
    public $refundOrderId = null;
    public $refundReason = '';
    public $refundType = '';
    // Rating Modal
    public $currentDetailId = null;
    public $showRatingModal = false;
    public $selectedOrderId = null;
    public $ratings = [];
    public $comments = [];
    public $rating = 0;
    public $images = [];
    public $anonymous = [];
    public $showViewRatingModal = false;
    public $viewRatings = [];

    public $paymentMethod;
    public $banks = [];
    public $bank_code;
    public $bank_account_number;
    public $bank_account_name;

    public $reasons = [
        'Tôi đặt nhầm',
        'Thời gian giao hàng quá lâu',
        'Muốn thay đổi sản phẩm',
        'Tìm được giá tốt hơn',
        'Lý do khác'
    ];

    public function mount(VietQRService $vietQR)
    {
        try {
            $this->banks = $vietQR->getBanks();
        } catch (\Exception $e) {
            $this->banks = [];
            Log::error('Error fetching banks from VietQR: ' . $e->getMessage());
        }
    }
    // =====================
    // return/refund Modal
    // =====================
    public function openRefundModal($orderId, $type)
    {
        $order = OrderModel::with('paymentDetail')->find($orderId);
        $this->paymentMethod = $order->paymentDetail?->payment_method; // null-safe
        $this->refundOrderId = $orderId;
        $this->refundType = $type; // 'refund' hoặc 'return'
        $this->refundReason = '';
        $this->showRefundModal = true;
    }

    public function confirmRefundRequest()
    {
        $order = OrderModel::find($this->refundOrderId);

        if (!$order) {
            $this->dispatch('toast', type: 'error', message: 'Không tìm thấy đơn hàng!');
            return;
        }

        // Validate lý do (luôn bắt buộc)
        $this->validate([
            'refundReason' => 'required|string',
        ], [
            'refundReason.required' => 'Vui lòng nhập lý do!',
        ]);

        // Nếu đơn hàng thanh toán qua PayOS → validate thêm thông tin ngân hàng
        if ($order->paymentDetail?->payment_method === 'payos' || $order->paymentDetail?->payment_method === 'vnpay') {
            $this->validate([
                'bank_code'            => 'required|string',
                'bank_account_name'    => 'required|string',
                'bank_account_number'  => 'required|string',
            ], [
                'bank_code.required'            => 'Vui lòng chọn ngân hàng!',
                'bank_account_name.required'    => 'Vui lòng nhập tên chủ tài khoản!',
                'bank_account_number.required'  => 'Vui lòng nhập số tài khoản!',
            ]);
        }

        // Xử lý logic thay đổi trạng thái
        if (
            $this->refundType === 'refund'
            && $order->is_paid === 1
            && in_array($order->orders_status, ['Chờ duyệt', 'Đã thanh toán'])
        ) {
            $order->update([
                'orders_status'        => 'Chờ hoàn tiền',
                'reason'               => $this->refundReason,
                'bank_code'            => ($order->paymentDetail?->payment_method === 'payos' || $order->paymentDetail?->payment_method === 'vnpay') ? $this->bank_code : null,
                'bank_account_name'    => ($order->paymentDetail?->payment_method === 'payos' || $order->paymentDetail?->payment_method === 'vnpay') ? $this->bank_account_name : null,
                'bank_account_number'  => ($order->paymentDetail?->payment_method === 'payos' || $order->paymentDetail?->payment_method === 'vnpay') ? $this->bank_account_number : null,
            ]);

            $this->dispatch('toast', type: 'success', message: 'Đã gửi yêu cầu hoàn tiền!');
        } elseif ($this->refundType === 'return' && $order->orders_status === 'Đã giao') {
            $order->update([
                'orders_status'        => 'Chờ trả hàng',
                'reason'               => $this->refundReason,
                'bank_code'            => ($order->paymentDetail?->payment_method === 'payos' || $order->paymentDetail?->payment_method === 'vnpay') ? $this->bank_code : null,
                'bank_account_name'    => ($order->paymentDetail?->payment_method === 'payos' || $order->paymentDetail?->payment_method === 'vnpay') ? $this->bank_account_name : null,
                'bank_account_number'  => ($order->paymentDetail?->payment_method === 'payos' || $order->paymentDetail?->payment_method === 'vnpay') ? $this->bank_account_number : null,
            ]);

            $this->dispatch('toast', type: 'success', message: 'Đã gửi yêu cầu trả hàng & hoàn tiền!');
        } else {
            $this->dispatch('toast', type: 'error', message: 'Trạng thái đơn hàng không hợp lệ!');
        }

        // Reset dữ liệu form
        $this->reset([
            'showRefundModal',
            'refundOrderId',
            'refundReason',
            'refundType',
            'bank_code',
            'bank_account_number',
            'bank_account_name'
        ]);
    }




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
        $this->validate([
            'selectedReason' => 'required',
            'customReason'   => 'required_if:selectedReason,Lý do khác',
        ], [
            'selectedReason.required' => 'Vui lòng chọn lý do hủy.',
            'customReason.required_if' => 'Vui lòng nhập lý do hủy.',
        ]);

        $order = OrderModel::find($this->orderId);

        if (!$order) {
            $this->dispatch('toast', type: 'error', message: 'Không tìm thấy đơn hàng.');
            return;
        }

        $reason = $this->selectedReason === 'Lý do khác'
            ? $this->customReason
            : $this->selectedReason;

        DB::transaction(function () use ($order, $reason) {
            $order->update([
                'orders_status' => 'Đã hủy',
                'reason' => $reason,
            ]);

            activity()
                ->causedBy(Auth::user())
                ->performedOn($order)
                ->withProperties(['role' => Auth::user()?->role ?? 'client'])
                ->log('Người dùng hủy đơn hàng: ' . $this->orderId);
        });

        $this->reset(['showCancelModal', 'selectedReason', 'customReason', 'orderId']);
        $this->dispatch('toast', type: 'success', message: 'Đã hủy đơn thành công');
    }

    // =====================
    // RATING
    // =====================
    public function viewRating($orderId)
    {


        $order = \App\Models\Order::with('orderDetails')->find($orderId);
        $ratings = [];
        foreach ($order->orderDetails as $detail) {
            $rating = \App\Models\Rating::where('user_id', Auth::id())
                ->where('order_detail_id', $detail->id)
                ->first();
            if ($rating) {
                $ratings[] = $rating;
            }
        }
        $this->viewRatings = $ratings;
        $this->showViewRatingModal = true;
    }
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
                "images.{$detail->id}" => 'nullable|array|max:3',
                "images.{$detail->id}.*" => 'file|max:10240|mimes:jpg,jpeg,png,webp,mp4,mov,avi,mpeg,3gp,webm',
            ], [
                "ratings.{$detail->id}.required" => 'Vui lòng chọn số sao.',
                "comments.{$detail->id}.required" => 'Vui lòng nhập nhận xét.',
                "comments.{$detail->id}.min" => 'Nhận xét tối thiểu 10 ký tự.',
                "images.{$detail->id}.max" => 'Chỉ được chọn tối đa 3 file.',
                "images.{$detail->id}.*.file" => 'File không hợp lệ.',
                "images.{$detail->id}.*.max" => 'Mỗi file tối đa 10MB.',
                "images.{$detail->id}.*.mimes" => 'Chỉ cho phép các định dạng: jpg, jpeg, png, webp, mp4, mov, avi, mpeg, 3gp, webm.',
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
                        $q->where('orders_status', 'Chờ duyệt');
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
    public function requestRefund($orderId)
    {
        $order = OrderModel::find($orderId);
        if ($order && $order->orders_status === 'Đã thanh toán') {
            $order->update(['orders_status' => 'Chờ hoàn tiền']);
            $this->dispatch('toast', type: 'success', message: 'Đã gửi yêu cầu hoàn tiền!');
        } else {
            $this->dispatch('toast', type: 'error', message: 'Chỉ gửi hoàn tiền cho đơn đã thanh toán!');
        }
    }

    public function requestReturnRefund($orderId)
    {

        $order = OrderModel::find($orderId);
        if ($order && $order->orders_status === 'Đã giao') {
            $order->update(['orders_status' => 'Chờ trả hàng']);
            $this->dispatch('toast', type: 'success', message: 'Đã gửi yêu cầu trả hàng & hoàn tiền!');
        } else {
            $this->dispatch('toast', type: 'error', message: 'Chỉ gửi trả hàng cho đơn đã giao!');
        }
    }

    public function render()
    {
        return view('usermodule::livewire.components.order', [
            'orders' => $this->orders,
        ]);
    }
}
