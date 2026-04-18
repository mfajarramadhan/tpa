<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function index()
{
    $fee = Fee::first();
    return view('fees.index', compact('fee'));
}

public function update(Request $request)
{
    $fee = \App\Models\Fee::first();

    $fee->update([
        'registration_fee' => $request->registration_fee,
        'monthly_fee' => $request->monthly_fee
    ]);

    return back()->with('success', 'Biaya berhasil diperbarui');
}
}
