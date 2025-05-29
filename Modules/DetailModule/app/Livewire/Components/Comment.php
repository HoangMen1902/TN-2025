<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\OrderDetail;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Comment extends Component
{
    public $id; // product_id
    public $rating = 0;
    public $review = '';
    public $showModal = false;

    public function writeReview()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->showModal = true;
    }

    public function mount($id)
    {
        $this->id = $id;
    }

    public function submitReview()
    {
        Log::info('submitReview called', [
            'user_id' => Auth::id(),
            'product_id' => $this->id,
            'rating' => $this->rating,
            'review' => $this->review,
        ]);

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:5|max:500',
        ], [
            'rating.required' => 'Bạn chưa chọn đánh giá sao.',
            'rating.integer' => 'Giá trị đánh giá không hợp lệ.',
            'rating.min' => 'Đánh giá phải lớn hơn hoặc bằng 1 sao.',
            'rating.max' => 'Đánh giá không được vượt quá 5 sao.',

            'review.required' => 'Bạn chưa nhập nội dung đánh giá.',
            'review.string' => 'Nội dung đánh giá không hợp lệ.',
            'review.min' => 'Nội dung đánh giá phải có ít nhất :min ký tự.',
            'review.max' => 'Nội dung đánh giá không được vượt quá :max ký tự.',
        ]);


        $orderDetail = OrderDetail::whereHas('order', function ($q) {
            $q->where('user_id', Auth::id())
                ->where('orders_status', 'Đã giao');
        })->whereHas('sku', function ($q) {
            $q->where('product_id', $this->id);
        })->first();

        if (!$orderDetail) {
            Log::warning('User tried to review without valid order detail', [
                'user_id' => Auth::id(),
                'product_id' => $this->id,
            ]);
            $this->dispatch('toast', type: 'error', message: 'Bạn chỉ có thể đánh giá sau khi mua và nhận hàng.');

            return;
        }

        Rating::create([
            'order_detail_id' => $orderDetail->id,
            'user_id' => Auth::id(),
            'review' => $this->review,
            'rating' => $this->rating,
            'status' => 1,
        ]);

        Log::info('Review created successfully', [
            'user_id' => Auth::id(),
            'product_id' => $this->id,
            'order_detail_id' => $orderDetail->id,
        ]);

        $this->reset(['rating', 'review', 'showModal']);
        $this->dispatch('toast', type: 'success', message: 'Đánh giá đã được gửi.');
    }


    public function render()
    {
        $ratings = Rating::whereHas('orderDetail.sku', function ($q) {
            $q->where('product_id', $this->id);
        })->with(['user'])->get();

        return view('detailmodule::livewire.components.comment', [
            'ratings' => $ratings,
        ]);
    }
}
