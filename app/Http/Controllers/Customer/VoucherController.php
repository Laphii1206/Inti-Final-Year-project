<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Voucher;

class VoucherController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $availableVouchers = Voucher::where('user_id', $user->id)
            ->where('status', 'available')
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->orderByDesc('created_at')
            ->get();

        $usedVouchers = Voucher::where('user_id', $user->id)
            ->where('status', 'used')
            ->orderByDesc('used_at')
            ->get();

        $expiredVouchers = Voucher::where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('status', 'expired')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'available')
                         ->where('expires_at', '<', now());
                  });
            })
            ->orderByDesc('expires_at')
            ->get();

        return view('customer.vouchers.index', compact(
            'availableVouchers', 'usedVouchers', 'expiredVouchers'
        ));
    }
}
