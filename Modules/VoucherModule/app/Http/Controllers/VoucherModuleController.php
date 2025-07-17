<?php

namespace Modules\VoucherModule\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;
use App\Models\VoucherUsed;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserPoint;
use App\Models\PointTransaction;
use Illuminate\Support\Facades\DB;
use Exception;

class VoucherModuleController extends Controller
{
    public function index(Request $request)
    {
        $query = Voucher::where('voucher_status', 1)
            ->whereNotNull('expired_at')
            ->where('expired_at', '>', now())
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('issued_by', 'point')
                        ->whereNotNull('required_points');
                })
                    ->orWhere('issued_by', 'manual');
            });

        if ($request->filled('keyword')) {
            $query->where('voucher_name', 'like', '%' . $request->keyword . '%');
        }

        $vouchers = $query->orderBy('expired_at', 'asc')->get();

        $userId = Auth::id();
        $usedVouchers = VoucherUsed::where('user_id', $userId)->pluck('voucher_id')->toArray();

        foreach ($vouchers as $voucher) {
            $voucher->usedByCurrentUser = in_array($voucher->id, $usedVouchers);
        }

        return view('vouchermodule::index', compact('vouchers'));
    }


    public function create()
    {
        return view('vouchermodule::create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
        ]);

        $user = Auth::user();
        $voucherId = $request->input('voucher_id');

        try {
            DB::beginTransaction();

            $voucher = Voucher::where('id', $voucherId)
                ->where('issued_by', 'manual')
                ->where('voucher_status', 1)
                ->where('expired_at', '>', now())
                ->where('quantity', '>', 0)
                ->lockForUpdate()
                ->first();

            if (!$voucher) {
                return redirect()->back()->with('error', 'Voucher không hợp lệ, đã hết hạn hoặc đã hết số lượng.');
            }

            $alreadySaved = VoucherUsed::where('voucher_id', $voucherId)
                ->where('user_id', $user->id)
                ->exists();

            if ($alreadySaved) {
                return redirect()->back()->with('error', 'Bạn đã lưu voucher này rồi.');
            }

            $voucher->decrement('quantity');

            VoucherUsed::create([
                'voucher_id' => $voucherId,
                'user_id' => $user->id,
                'is_used' => false,
                'status' => 1,
                'used_at' => null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Lưu voucher thành công!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi lưu voucher: ' . $e->getMessage());
        }
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'voucher_id' => 'required|exists:vouchers,id',
        ]);

        $user = Auth::user();
        $voucherId = $request->input('voucher_id');

        try {
            DB::beginTransaction();

            $voucher = Voucher::where('id', $voucherId)
                ->where('issued_by', 'point')
                ->where('voucher_status', 1)
                ->where('expired_at', '>', now())
                ->where('quantity', '>', 0)
                ->lockForUpdate()
                ->first();

            if (!$voucher) {
                return redirect()->back()->with('error', 'Voucher không hợp lệ, đã hết hạn hoặc đã hết số lượng.');
            }

            $userPoint = $user->point;

            if (!$userPoint || $userPoint->redeemable_points < $voucher->required_points) {
                return redirect()->back()->with('error', 'Bạn không đủ điểm để đổi voucher này.');
            }

            $alreadyRedeemed = VoucherUsed::where('voucher_id', $voucherId)
                ->where('user_id', $user->id)
                ->exists();

            if ($alreadyRedeemed) {
                return redirect()->back()->with('error', 'Bạn đã đổi voucher này rồi.');
            }

            $userPoint->decrement('redeemable_points', $voucher->required_points);

            $voucher->decrement('quantity');

            PointTransaction::create([
                'user_id' => $user->id,
                'points' => -$voucher->required_points,
                'type' => 'redeem',
                'source' => 'voucher',
            ]);

            VoucherUsed::create([
                'voucher_id' => $voucherId,
                'user_id' => $user->id,
                'is_used' => false,
                'status' => 1,
                'used_at' => null,
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Đổi voucher thành công!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi đổi voucher: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        return view('vouchermodule::show');
    }

    public function edit($id)
    {
        return view('vouchermodule::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
