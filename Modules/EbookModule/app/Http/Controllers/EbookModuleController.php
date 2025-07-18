<?php

namespace  Modules\EbookModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductEbook;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class EbookModuleController extends Controller
{
    public function index()
    {
        $ebooks = ProductEbook::all();
        return view('ebookmodule::index_list', compact('ebooks'));
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

        return view('ebookmodule::index', [
            'ebook' => $ebook,
            'chapters' => $chapters,
            'chapterNumber' => $chapterNumber,
            'hasPurchased' => $hasPurchased,
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
                'categories' => collect([]), // hoặc null nếu không dùng
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
