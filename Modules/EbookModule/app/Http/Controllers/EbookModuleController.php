<?php

namespace  Modules\EbookModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductEbook;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
