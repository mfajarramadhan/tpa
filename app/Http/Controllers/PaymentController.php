<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // QUERY DASAR
        if ($user->hasRole('orang_tua')) {
            $query = Payment::whereHas('student', function ($q) use ($user) {
                $q->where('parent_id', $user->id);
            })->with('student');
        } else {
            $query = Payment::with('student');
        }

        // FILTER (TAMBAHAN)
        if (request('month')) {
            $query->where('month', request('month'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        // AMBIL DATA
        $payments = $query->orderBy('month', 'desc')->get();

        // TOTAL (TAMBAHAN)
        $totalUnpaid = $payments->where('status', 'pending')->sum('amount');
        $totalPaid   = $payments->where('status', 'paid')->sum('amount');

        return view('payments.index', compact('payments', 'totalUnpaid', 'totalPaid'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = Auth::user()->students;

        return view('payments.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'month' => 'required',
            'amount' => 'required|numeric',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        // cek apakah tagihan bulan ini sudah ada
        $payment = Payment::where('student_id', $request->student_id)
            ->where('month', $request->month)
            ->where('type', 'monthly')
            ->first();

        // ambil tunggakan sebelumnya (exclude bulan ini)
        $previousUnpaid = Payment::where('student_id', $request->student_id)
            ->where('status', 'pending')
            ->where('month', '!=', $request->month)
            ->sum('amount');

        // hitung total
        $total = $request->amount + $previousUnpaid;

        // upload file
        $path = $request->file('proof_file')->store('payments', 'public');

        // kalau sudah ada → update, kalau belum → create
        Payment::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'month' => $request->month,
                'type' => 'monthly',
            ],
            [
                'original_amount' => $request->amount,
                'amount' => $total,
                'proof_file' => $path,
                'status' => 'pending'
            ]
        );

        return redirect()->route('payments.index')->with('success', 'Bukti pembayaran berhasil diupload');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();

        $student = Student::findOrFail($id);

        // orang tua hanya boleh lihat anak sendiri
        if ($user->hasRole('orang_tua') && $student->parent_id != $user->id) {
            abort(403);
        }

        $payments = Payment::where('student_id', $id)
            ->orderBy('month', 'desc')
            ->get();

        $totalUnpaid = $payments->where('status', 'pending')->sum('amount');
        $totalPaid = $payments->where('status', 'paid')->sum('amount');

        return view('payments.show', compact('student', 'payments', 'totalUnpaid', 'totalPaid'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $payment->original_amount = $request->original_amount;

        // hitung ulang total
        $previousUnpaid = Payment::where('student_id', $payment->student_id)
            ->where('status', 'pending')
            ->where('id', '!=', $payment->id)
            ->sum('amount');

        $payment->amount = $request->original_amount + $previousUnpaid;

        $payment->save();

        return redirect()->route('payments.index')
            ->with('success', 'Nominal berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function approve($id)
    {
        $payment = Payment::findOrFail($id);

        if ($payment->status == 'paid') {
            return back()->with('error', 'Sudah lunas');
        }

        $payment->update([
            'status' => 'paid',
            'approved_by' => Auth::id()
        ]);

        return back()->with('success', 'Pembayaran disetujui');
    }

    public function unapprove($id)
    {
        $payment = Payment::findOrFail($id);

        if (!Auth::user()->hasRole('superadmin')) {
            abort(403);
        }

        $payment->update([
            'status' => 'pending',
            'approved_by' => null
        ]);

        return back()->with('success', 'Pembayaran dibatalkan');
    }
}
