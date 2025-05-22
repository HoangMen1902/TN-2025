<?php

namespace Modules\PaymentModule\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class PaymentModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect('/gio-hang');
    }

    /**
     * Show the form for creating a new resource.
     */

    public function paymentPage(Request $request) {
        $cartIds = $request->input('cart_id');

        $carts = Cart::whereIn('id', $cartIds)->get();
        return view('paymentmodule::index', ['carts' => $carts]);
    }
    public function create()
    {
        return view('paymentmodule::create');
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
        return view('paymentmodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('paymentmodule::edit');
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
