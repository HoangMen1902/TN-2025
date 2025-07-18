<?php

namespace Modules\ViettelPostWebhook\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ViettelPostWebhookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('viettelpostwebhook::create');
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
        return view('viettelpostwebhook::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('viettelpostwebhook::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}

    public function handle(Request $request)
    {
        $header = $request->header('Authorization');
        $provided_token = env('VIETTELPOST_WEBHOOK_TOKEN');

        if ($header !== $provided_token) {
            Log::warning('Request headers:', $request->headers->all());
            Log::warning('Webhook: Token mismatch', ['received' => $header]);
            return response()->json(['error' => 'Token không hợp lệ'], 401);
        }

        $payload = $request->all();
    }
}
