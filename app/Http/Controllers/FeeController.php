<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
    {
        $fee = Fee::firstOrFail();
        return view('fees.index', compact('fee'));
    }

public function update(Request $request)
    {
        $request->validate([
            'registration_fee' => 'required|integer|min:0',
            'monthly_fee' => 'required|integer|min:0',
        ]);

        $fee = Fee::firstOrFail();

        $fee->update([
            'registration_fee' => $request->registration_fee,
            'monthly_fee' => $request->monthly_fee
        ]);

        return redirect()->route('fees.index')->with('success', 'Biaya berhasil diperbarui');
    }   
}
