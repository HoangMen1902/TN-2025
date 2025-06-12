<?php

namespace Modules\EbookModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductEbook;

class EbookModuleController extends Controller
{


    public function show($id)
    {
        $ebook = ProductEbook::with('product')->findOrFail($id);

        return view('ebookmodule::index', compact('ebook'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('ebookmodule::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ebookmodule::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    // public function show($id)
    // {
    //     return view('ebookmodule::show');
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('ebookmodule::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
