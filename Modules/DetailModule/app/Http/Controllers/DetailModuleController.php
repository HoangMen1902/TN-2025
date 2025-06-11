<?php

namespace Modules\DetailModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCombo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DetailModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($slug)
    {
        $data = Product::with('sku')
            ->where('slug', $slug)
            ->where('product_status', 'active')
            ->where('deleted_at', '=', null)
            ->first();
        if ($data != null) {

            $id = $data->id;

            return view('detailmodule::index', ['data' => $data, 'id' => $id, 'type' => 'product']);
        } else {
            return redirect('/');
        }
    }


    public function combo($slug)
    {
        $data = ProductCombo::where('slug', $slug)->first();
        if ($data != null) {

            $id = $data->id;

            return view('detailmodule::index', ['data' => $data, 'id' => $id, 'type' => 'combo']);
        } else {
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('detailmodule::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('detailmodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('detailmodule::edit');
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
