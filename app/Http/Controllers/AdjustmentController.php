<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class AdjustmentController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer'
        ]);

        $adjustment = $request->amount;

        // 🔥 hanya tagihan bulanan yang belum dibayar
        $payments = Payment::where('type', 'monthly')
            ->where('status', 'pending')
            ->get();

        foreach ($payments as $payment) {
            $payment->update([
                'adjustment' => $adjustment
            ]);
        }

        return back()->with('success', 'Penyesuaian berhasil diterapkan ke semua tagihan pending');
    }
}