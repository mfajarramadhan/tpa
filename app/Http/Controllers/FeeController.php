<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;
use App\Models\FeeLog;
use Illuminate\Support\Facades\Auth;

class FeeController extends Controller
{
    public function index()
    {
        $fee = Fee::firstOrFail();
        $logs = FeeLog::with('user')->latest()->limit(10)->get();

        return view('fees.index', compact('fee', 'logs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'registration_fee' => 'required|integer|min:0',
            'monthly_fee' => 'required|integer|min:0',
        ]);

        $fee = Fee::firstOrFail();

        // 🔥 SIMPAN LOG SEBELUM UPDATE
        FeeLog::create([
            'user_id' => Auth::id(),
            'old_registration_fee' => $fee->registration_fee,
            'new_registration_fee' => $request->registration_fee,
            'old_monthly_fee' => $fee->monthly_fee,
            'new_monthly_fee' => $request->monthly_fee,
        ]);

        // UPDATE FEE
        $fee->update([
            'registration_fee' => $request->registration_fee,
            'monthly_fee' => $request->monthly_fee
        ]);

        return redirect()->route('fees.index')
            ->with('success', 'Biaya berhasil diperbarui');
    }
}
