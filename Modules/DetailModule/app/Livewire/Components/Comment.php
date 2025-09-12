<?php

namespace Modules\DetailModule\Livewire\Components;

use App\Models\OrderDetail;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;

class Comment extends Component
{
    use WithFileUploads;
    public $showEditModal = false;
    public $editRatingId = null;
    public $editReview = '';
    public $editRating = 0;
    public $editImages = [];
    public $id;
    public $rating = 0;
    public $review = '';
    public $images = [];
    public $is_anonymous = false;
    public $showModal = false;
    public $averageRating = 0;
    public $totalReviews = 0;
    public $ratingsCount = [
        5 => 0,
        4 => 0,
        3 => 0,
        2 => 0,
        1 => 0,
    ];
    public $likedRatings = [];
    public $selectedFavorite = false;
    public $ratingsPercentage = [];
    public $ratings;
    public $ratingsNewest;
    public $ratingsFavorite;
    public function writeReview()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->showModal = true;
    }


    public function submitEditReview()
    {
        $this->validate([
            'editRating' => 'required|integer|min:1|max:5',
            'editReview' => 'required|string|min:5|max:500',
            // Có thể thêm validate cho editImages nếu cho phép sửa ảnh/video
        ]);
        $rating = Rating::find($this->editRatingId);
        if (!$rating || $rating->user_id !== Auth::id()) {
            $this->dispatch('toast', type: 'error', message: 'Bạn không có quyền sửa đánh giá này.');
            return;
        }
        if (\Carbon\Carbon::parse($rating->created_at)->diffInDays(now()) >= 7) {
            $this->dispatch('toast', type: 'error', message: 'Chỉ được sửa đánh giá trong vòng 1 tuần.');
            return;
        }
        $rating->update([
            'review' => $this->editReview,
            'rating' => $this->editRating,
            // 'images' => json_encode($this->editImages), // nếu cho sửa ảnh/video
        ]);
        $this->showEditModal = false;
        $this->dispatch('toast', type: 'success', message: 'Đã sửa đánh giá thành công.');
        $this->loadRatings();
    }
    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }
    public function loadRatings()
    {
        $ratingsQuery = Rating::whereHas('orderDetail.sku', function ($q) {
            $q->where('product_id', $this->id);
        })->with(['user', 'likedUsers']);

        $this->ratings = $ratingsQuery->get();
        $this->ratingsNewest = $this->ratings->sortByDesc('created_at');
        $this->ratingsFavorite = $this->ratings->sortByDesc(function ($rating) {
            return $rating->likedUsers->count();
        });
    }

    public function mount($id)
    {
        $this->id = $id;

        if (Auth::check()) {
            $this->likedRatings = \App\Models\UserLikedRating::where('user_id', Auth::id())
                ->pluck('rating_id')
                ->toArray();
        }

        $this->loadStats();
        $this->loadRatings();
    }
    public function toggleLike($ratingId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $liked = \App\Models\UserLikedRating::where('user_id', Auth::id())
            ->where('rating_id', $ratingId)
            ->first();

        if ($liked) {
            $liked->delete();
            $this->likedRatings = array_diff($this->likedRatings, [$ratingId]);
        } else {
            \App\Models\UserLikedRating::create([
                'user_id' => Auth::id(),
                'rating_id' => $ratingId,
            ]);
            $this->likedRatings[] = $ratingId;
        }

        $this->loadRatings();
    }


    public function loadStats()
    {
        $ratings = Rating::whereHas('orderDetail.sku', function ($query) {
            $query->where('product_id', $this->id);
        })->get();

        $this->totalReviews = $ratings->count();

        $this->ratingsCount = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($ratings as $rating) {
            $this->ratingsCount[$rating->rating]++;
        }

        if ($this->totalReviews > 0) {
            $sum = 0;
            foreach ($this->ratingsCount as $star => $count) {
                $sum += $star * $count;
            }
            $this->averageRating = round($sum / $this->totalReviews, 1);

            foreach ($this->ratingsCount as $star => $count) {
                $this->ratingsPercentage[$star] = round(($count / $this->totalReviews) * 100);
            }
        } else {
            $this->averageRating = 0;
            $this->ratingsPercentage = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        }
    }

    public function update() {}

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
            'images' => 'nullable|array|max:3',
            'images.*' => 'file|max:10240|mimes:jpg,jpeg,png,webp,mp4,mov,avi,mpeg,3gp,webm',

        ], [

            'rating.required' => 'Bạn chưa chọn đánh giá sao.',
            'rating.integer' => 'Giá trị đánh giá không hợp lệ.',
            'rating.min' => 'Đánh giá phải lớn hơn hoặc bằng 1 sao.',
            'rating.max' => 'Đánh giá không được vượt quá 5 sao.',
            'images.max' => 'Chỉ được chọn tối đa 3 ảnh.',
            'images.*.image' => 'File phải là ảnh.',
            'images.*.max' => 'Mỗi file tối đa 10MB.',
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
            $this->dispatch('toast', type: 'error', message: 'Bạn chỉ có thể đánh giá sau khi mua và nhận hàng.');
            return;
        }

        $alreadyRated = Rating::where('order_detail_id', $orderDetail->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyRated) {
            $this->dispatch('toast', type: 'warning', message: 'Bạn đã đánh giá sản phẩm này.');
            return;
        }

        $imagePaths = [];
        if (!empty($this->images)) {
            foreach ($this->images as $img) {
                $imagePaths[] = $img->store('ratings', 'public');
            }
        }
        $imagePaths = [];
        if (!empty($this->images)) {
            foreach ($this->images as $img) {
                $imagePaths[] = $img->store('ratings', 'public');
            }
        }
        Rating::create([
            'order_detail_id' => $orderDetail->id,
            'user_id' => Auth::id(),
            'review' => $this->review,
            'rating' => $this->rating,
            'status' => 1,
            'is_anonymous' => $this->is_anonymous ? 1 : 0,
            'images' => json_encode($imagePaths),
        ]);
        Log::info('Review created successfully', [
            'user_id' => Auth::id(),
            'product_id' => $this->id,
            'order_detail_id' => $orderDetail->id,
        ]);

        $this->reset(['rating', 'review', 'showModal']);
        $this->dispatch('toast', type: 'success', message: 'Đánh giá đã được gửi.');
    }

    public function editReviewModal($ratingId)
    {
        Log::info('editReview called', ['id' => $ratingId]);
        $rating = Rating::find($ratingId);
        if (!$rating) {
            Log::info('Rating not found', ['id' => $ratingId]);
            $this->dispatch('toast', type: 'error', message: 'Không tìm thấy đánh giá.');
            return;
        }
        if ($rating->user_id !== Auth::id()) {
            Log::info('User not owner', [
                'rating_user_id' => $rating->user_id,
                'auth_id' => Auth::id()
            ]);
            $this->dispatch('toast', type: 'error', message: 'Bạn không có quyền sửa đánh giá này.');
            return;
        }
        if (\Carbon\Carbon::parse($rating->created_at)->diffInDays(now()) >= 7) {
            Log::info('Rating too old', [
                'created_at' => $rating->created_at,
                'now' => now()
            ]);
            $this->dispatch('toast', type: 'error', message: 'Chỉ được sửa đánh giá trong vòng 1 tuần.');
            return;
        }
        Log::info('Show edit modal', ['id' => $ratingId]);
        $this->editRatingId = $ratingId;
        $this->editReview = $rating->review;
        $this->editRating = $rating->rating;
        $this->editImages = is_array($rating->images) ? $rating->images : json_decode($rating->images, true);
        $this->showEditModal = true;
    }
    public function render()
    {
        return view('detailmodule::livewire.components.comment', [
            'ratings' => $this->ratings,
            'ratingsNewest' => $this->ratingsNewest,
            'ratingsFavorite' => $this->ratingsFavorite,
        ]);
    }
}
