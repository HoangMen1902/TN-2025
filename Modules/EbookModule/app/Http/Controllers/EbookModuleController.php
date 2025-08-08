<?php

namespace  Modules\EbookModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EbookAudioJob;
use App\Models\ProductEbook;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\UserEbookChapterStatus;
use Illuminate\Support\Facades\Log;

class EbookModuleController extends Controller
{
    public function index()
    {
        $ebooks = ProductEbook::all();
        $userId = Auth::id();

        $position = null;

        if ($userId) {
            $position = UserEbookChapterStatus::where('user_id', $userId)
                ->orderByDesc('reading_position')
                ->value('reading_position') ?? 0;
        }

        return view('ebookmodule::index_list', compact('ebooks', 'position'));
    }

    public function show($ebookId)
    {
        $ebook = ProductEbook::with('chapters')->findOrFail($ebookId);
        $chapters = $ebook->chapters;

        $chapterNumber = request()->query('chapter', 1);
        $chapter = $chapters[$chapterNumber - 1] ?? null;

        if (!$chapter) {
            abort(404);
        }

        $user = Auth::id();
        $hasPurchased = false;

        if ($user) {
            $hasPurchased = $ebook->isPurchasedBy($user);
        }

        if ($chapter->is_locked && !$hasPurchased) {
            abort(403, 'Bạn cần thanh toán để đọc chương này');
        }
        $status = null;
        if ($user && $chapter) {
            $status = UserEbookChapterStatus::where([
                'user_id' => $user,
                'ebook_id' => $ebook->id,
                'chapter_id' => $chapter->id,
            ])->first();
        }
        $readingPosition = $status?->position ?? 0;
        $query = EbookAudioJob::where('ebook_id', $ebook->id)
            ->whereJsonContains('chapter_ids', (string) $chapter->id)
            ->whereNotNull('output_path');
        $audioJobs = $query->get();
        return view('ebookmodule::index', [
            'ebook' => $ebook,
            'chapters' => $chapters,
            'chapterNumber' => $chapterNumber,
            'hasPurchased' => $hasPurchased,
            'isRead' => $status?->is_read ?? false,
            'isFavorite' => $status?->is_favorite ?? false,
            'position' => $status?->reading_position ?? 0,
            'audioJobs' => $audioJobs,
        ]);
    }
    public static function mapEbookToProductFormat()
    {
        return ProductEbook::all()->map(function ($ebook) {
            $price = $ebook->price;
            $salePrice = $ebook->sale_price ?? $price;
            $isSale = ($ebook->sale_price && $ebook->sale_price < $ebook->price);
            $discount = ($price > 0 && $isSale) ? round((($price - $salePrice) / $price) * 100, 2) : 0;

            return (object)[
                'id' => $ebook->id,
                'slug' => Str::slug($ebook->title),
                'name' => $ebook->title,
                'thumbnail' => $ebook->cover_image,
                'price' => $price,
                'sale_price' => $salePrice,
                'discount' => $discount,
                'categories' => collect([]),
                'first_sku' => null,
                'rating' => rand(3, 5),
                'review_count' => rand(5, 100),
                'sold' => 0,
                'total' => 0,
                'percent_sold' => 0,
                'is_ebook' => true, // quan trọng
            ];
        });
    }

    public function updatePosition(Request $request, $ebookId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }

        $chapterId = $request->input('chapter_id');
        $scrollPercent = $request->input('scroll_percent');

        if (!$chapterId || !is_numeric($scrollPercent)) {
            return response()->json(['error' => 'Dữ liệu không hợp lệ'], 422);
        }

        $status = UserEbookChapterStatus::updateOrCreate(
            [
                'user_id' => $userId,
                'ebook_id' => $ebookId,
                'chapter_id' => $chapterId,
            ],
            [
                'reading_position' => $scrollPercent,
            ]
        );

        return response()->json(['success' => true, 'position' => $scrollPercent]);
    }


    public function toggleRead($chapterId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }
        $chapter = \App\Models\EbookChapter::findOrFail($chapterId);
        $ebookId = $chapter->ebook_id;

        $status = UserEbookChapterStatus::firstOrNew([
            'user_id' => $userId,
            'chapter_id' => $chapterId,
        ]);

        $status->ebook_id = $ebookId; // Thêm dòng này
        $status->is_read = !$status->is_read;
        $status->save();

        return back();
    }
    public function toggleFavorite($chapterId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('login');
        }
        $chapter = \App\Models\EbookChapter::findOrFail($chapterId);
        $ebookId = $chapter->ebook_id;

        $status = UserEbookChapterStatus::firstOrNew([
            'user_id' => $userId,
            'chapter_id' => $chapterId,
        ]);

        $status->ebook_id = $ebookId; // Thêm dòng này
        $status->is_favorite = !$status->is_favorite;
        $status->save();

        return back();
    }

    public function create()
    {
        return view('EbookModule::create');
    }

    public function store(Request $request) {}

    public function edit($id)
    {
        return view('EbookModule::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
