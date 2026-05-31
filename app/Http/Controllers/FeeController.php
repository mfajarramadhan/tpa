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
        $logs = FeeLog::with('user')->latest()->paginate(3)->withQueryString();

        return view('fees.index', compact('fee', 'logs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'registration_fee' => 'required|integer|min:0',
            'monthly_fee' => 'required|integer|min:0',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'account_number' => ['nullable', 'regex:/^[0-9]{5,20}$/'],
        ]);

        $fee = Fee::firstOrFail();

        // 🔥 SIMPAN LOG SEBELUM UPDATE
        FeeLog::create([
            'user_id' => Auth::id(),
            'fee_id' => $fee->id,
            'old_registration_fee' => $fee->registration_fee,
            'new_registration_fee' => $request->registration_fee,
            'old_monthly_fee' => $fee->monthly_fee,
            'new_monthly_fee' => $request->monthly_fee,
            'old_bank_name' => $fee->bank_name,
            'new_bank_name' => $request->bank_name,
            'old_account_name' => $fee->account_name,
            'new_account_name' => $request->account_name,
            'old_account_number' => $fee->account_number,
            'new_account_number' => $request->account_number,
        ]);

        // UPDATE FEE
        $fee->update([
            'registration_fee' => $request->registration_fee,
            'monthly_fee' => $request->monthly_fee,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
        ]);

        return redirect()->route('fees.index')
            ->with('success', 'Informasi biaya berhasil diperbarui!');
    }
}
