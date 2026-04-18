<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // AUTO GENERATE IURAN
        $this->generateMonthlyBills();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // QUERY DASAR (KHUSUS MONTHLY)
        if ($user->hasRole('orang_tua')) {
            $query = Payment::where('type', 'monthly')
                ->whereHas('student', function ($q) use ($user) {
                    $q->where('parent_id', $user->id);
                })
                ->with('student');
        } else {
            $query = Payment::where('type', 'monthly')
                ->with('student');
        }

        // FILTER
        if (request('month')) {
            $query->where('month', request('month'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('student_name')) {
            $query->whereHas('student', function ($q) {
                $q->where('name', 'like', '%' . request('student_name') . '%');
            });
        }

        // AMBIL DATA
        $payments = $query->orderBy('month', 'asc')->get();
        // KHUSUS ORANG TUA → ambil daftar anak
        $students = collect();
        $selectedStudent = null;

        if ($user->hasRole('orang_tua')) {

            $students = Student::where('parent_id', $user->id)->where('status', 'aktif')->get();

            // pilih anak dari query (?student_id=)
            $selectedStudent = $students->firstWhere('id', request('student_id'));

            // default pilih anak pertama
            if (!$selectedStudent && $students->count()) {
                $selectedStudent = $students->first();
            }

            // filter payment berdasarkan anak terpilih
            if ($selectedStudent) {
                $payments = $payments->where('student_id', $selectedStudent->id)->values();
            }
        }

        // KHUSUS SUPERADMIN CEK KESELURUHAN TAGIHAN
        $studentsSummary = collect();

        if ($user->hasRole('superadmin')) {

            $studentsSummary = Student::with(['parent', 'classroom'])
                ->where('status', 'aktif')
                ->get()
                ->map(function ($student) {

                    $payments = $student->payments()
                        ->where('type', 'monthly')
                        ->get();

                    $totalTagihan = $payments->sum('original_amount');
                    $totalDibayar = $payments->where('status', 'paid')->sum('original_amount');

                    $status = 'Belum Bayar';

                    if ($totalDibayar > 0 && $totalDibayar < $totalTagihan) {
                        $status = 'Menunggak';
                    }

                    if ($totalDibayar == $totalTagihan && $totalTagihan > 0) {
                        $status = 'Lunas';
                    }

                    return [
                        'student' => $student,
                        'total_tagihan' => $totalTagihan,
                        'total_dibayar' => $totalDibayar,
                        'status' => $status,
                    ];
                });
        }

        // TOTAL GLOBAL
        $totalUnpaid = $payments->where('status', 'pending')->sum('amount');
        $totalPaid   = $payments->where('status', 'paid')->sum('amount');
        $allPayments = Payment::where('type', 'monthly')->get();
        $totalTagihanAll = $allPayments->sum('original_amount');
        $totalDibayarAll = $allPayments->where('status', 'paid')->sum('original_amount');
        $sisaTagihanAll  = $allPayments->where('status', 'pending')->sum('original_amount');

        return view('payments.index', compact(
            'payments',
            'totalUnpaid',
            'totalPaid',
            'students',
            'selectedStudent',
            'studentsSummary',
            'totalTagihanAll',
            'totalDibayarAll',
            'sisaTagihanAll'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $studentId = $request->student_id;

        // cari tagihan yang belum dibayar + belum upload
        $payment = Payment::where('student_id', $studentId)
            ->where('type', 'monthly')
            ->where(function ($q) {
                $q->whereNull('proof_file'); // belum upload
            })
            ->orderBy('month', 'asc')
            ->first();

        // HANDLE NULL
        if (!$payment) {
            return back()->with('error', 'Tidak ada tagihan yang harus dibayarkan!');
        }

        return view('payments.create', compact('payment'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // ambil payment
        $payment = Payment::with('student')->findOrFail($request->payment_id);

        // SECURITY: pastikan milik orang tua yg login
        if ($user->hasRole('orang_tua') && $payment->student->parent_id != $user->id) {
            abort(403, 'Tidak diizinkan');
        }

        // CEGah upload ulang jika sudah ada bukti
        if ($payment->proof_file) {
            return back()->with('error', 'Tagihan ini sudah diupload bukti pembayaran');
        }

        // upload file
        $path = $request->file('proof_file')->store('payments', 'public');

        // update payment (FIFO)
        $payment->update([
            'proof_file' => $path,
            'status' => 'pending', // menunggu approve admin
            'paid_at' => now() // generate tanggal bayar
        ]);

        return redirect()->route('payments.index')->with('success', 'Bukti pembayaran berhasil diupload');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();

        $student = Student::with(['parent', 'classroom'])->findOrFail($id);

        // proteksi orang tua
        if ($user->hasRole('orang_tua') && $student->parent_id != $user->id) {
            abort(403);
        }

        //  hanya monthly (untuk iuran)
        $payments = Payment::where('student_id', $id)
            ->where('type', 'monthly')
            ->orderBy('month', 'asc')
            ->get();

        $totalUnpaid = $payments->where('status', 'pending')->sum('original_amount');
        $totalPaid = $payments->where('status', 'paid')->sum('original_amount');

        return view('payments.show', compact(
            'student',
            'payments',
            'totalUnpaid',
            'totalPaid'
        ));
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
        if (!Auth::user()->hasRole('superadmin')) {
            abort(403);
        }

        $payment = Payment::with('student')->findOrFail($id);

        //  kalau sudah lunas
        if ($payment->status == 'paid') {
            return back()->with('error', 'Sudah lunas');
        }

        // totalUnpaid APPROVE REGISTRATION
        if ($payment->type == 'registration') {

            $student = $payment->student;

            // aktifkan siswa
            $student->update([
                'status' => 'aktif'
            ]);

            // aktifkan user siswa (kalau ada)
            if ($student->user) {
                $student->user->update([
                    'status' => 'aktif'
                ]);
            }

            $payment->update([
                'status' => 'paid',
                'approved_by' => Auth::id()
            ]);

            return back()->with('success', 'Siswa berhasil di-approve');
        }

        // 🔥 APPROVE IURAN BULANAN
        if ($payment->type == 'monthly') {

            $payment->update([
                'status' => 'paid',
                'approved_by' => Auth::id()
            ]);

            return back()->with('success', 'Iuran berhasil disetujui');
        }

        return back()->with('error', 'Tipe pembayaran tidak valid');
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

    private function generateMonthlyBills()
    {
        $students = Student::where('status', 'aktif')->get();

        foreach ($students as $student) {

            // tanggal daftar
            $createdAt = Carbon::parse($student->created_at);

            // bulan pertama iuran
            $firstBilling = $createdAt->copy()->addMonth()->startOfMonth();

            // bulan sekarang
            $now = Carbon::now()->startOfMonth();

            // looping dari bulan pertama sampai sekarang
            $current = $firstBilling->copy();

            while ($current <= $now) {

                $month = $current->format('Y-m');

                // cek apakah sudah ada tagihan
                $exists = Payment::where('student_id', $student->id)
                    ->where('type', 'monthly')
                    ->where('month', $month)
                    ->exists();

                if (!$exists) {
                    Payment::create([
                        'student_id' => $student->id,
                        'type' => 'monthly',
                        'month' => $month,
                        'original_amount' => 50000, // nanti bisa jadi setting
                        'amount' => 50000,
                        'status' => 'pending'
                    ]);
                }

                $current->addMonth();
            }
        }
    }
}
