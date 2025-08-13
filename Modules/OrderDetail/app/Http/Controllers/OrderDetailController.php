<?php

namespace Modules\OrderDetail\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentDetail;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tracking_id)
    {
        $map_box_api = env('MAP_BOX_API_KEY');
        $google_map_api = env('GOOGLE_MAP_API');
        $data = PaymentDetail::where('tracking_id', $tracking_id)->first();
        return view('orderdetail::index', ['data' => $data, 'api' => $map_box_api, 'google_map' => $google_map_api]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orderdetail::create');
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
        return view('orderdetail::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('orderdetail::edit');
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
