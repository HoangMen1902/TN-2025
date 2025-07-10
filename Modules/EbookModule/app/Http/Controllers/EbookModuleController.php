<?php

namespace  Modules\EbookModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductEbook;
use Illuminate\Http\Request;

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

        return view('ebookmodule::index', [
            'ebook' => $ebook,
            'chapters' => $chapters,
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
