<?php

namespace App\Http\Controllers;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use App\Notifications\PaymentApprovedNotification;
use App\Notifications\PaymentRejectedNotification;
use App\Notifications\PaymentUploadedNotification;
use App\Services\MonthlyBillService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(MonthlyBillService $monthlyBillService)
    {
        // auto generate iuran dari app/Services/MonthlyBillService.php
        $monthlyBillService->generate();

        /** @var User $user */
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

        // KHUSUS ORANG TUA → ambil daftar anak
        $students = collect();
        $selectedStudent = null;

        if ($user->hasRole('orang_tua')) {

            $students = Student::where('parent_id', $user->id)
                ->where('status', 'aktif')
                ->get();

            // pilih anak dari query (?student_id=)
            $selectedStudent = $students->firstWhere(
                'id',
                request('student_id')
            );

            // default pilih anak pertama
            if (!$selectedStudent && $students->count()) {

                $selectedStudent = $students->first();
            }

            // FILTER QUERY
            if ($selectedStudent) {

                $query->where(
                    'student_id',
                    $selectedStudent->id
                );
            }
        }

        // AMBIL DATA
        $payments = $query->latest('month')->paginate(10)->withQueryString();

        // KHUSUS SUPERADMIN CEK KESELURUHAN TAGIHAN
        $studentsSummary = collect();

        if ($user->hasRole('superadmin')) {

            $studentsSummary = Student::with([
                    'parent',
                    'classroom'
                ])
                ->where('status', 'aktif')
                ->get()
                ->map(function ($student) {

                    $studentPayments = $student->payments()
                        ->where('type', 'monthly')
                        ->get();

                    $totalTagihan = $studentPayments->sum('original_amount');

                    $totalDibayar = $studentPayments
                        ->where('status', 'paid')
                        ->sum('original_amount');

                    $sisaTagihan = $totalTagihan - $totalDibayar;

                    $status = 'Belum Bayar';

                    // cek kondisi penting
                    $ditolak = $studentPayments
                        ->where('status', 'rejected')
                        ->count() > 0;

                    $menungguKonfirmasi = $studentPayments
                        ->where('status', 'pending')
                        ->whereNotNull('proof_file')
                        ->count() > 0;

                    $belumBayar = $studentPayments
                        ->where('status', 'pending')
                        ->whereNull('proof_file')
                        ->count() > 0;

                    $adaTunggakan = $studentPayments
                        ->where('status', 'pending')
                        ->whereNull('proof_file')
                        ->filter(function ($payment) {
                            return $payment->month < now()->format('Y-m');
                        })
                        ->count() > 0;

                    // PRIORITAS FIFO
                    if ($ditolak) {
                        $status = 'Ditolak';

                    } elseif ($menungguKonfirmasi) {
                        $status = 'Menunggu Konfirmasi';

                    } elseif ($adaTunggakan || ($totalDibayar > 0 && $totalDibayar < $totalTagihan)) {
                        $status = 'Menunggak';

                    } elseif ($belumBayar) {
                        $status = 'Belum Bayar';

                    } elseif ($totalDibayar == $totalTagihan && $totalTagihan > 0) {
                        $status = 'Lunas';

                    } elseif ($totalTagihan == 0) {
                        $status = 'Tanpa tagihan';

                    } else {
                        $status = 'Belum Bayar';
                    }

                    return [
                        'student' => $student,
                        'total_tagihan' => $totalTagihan,
                        'total_dibayar' => $totalDibayar,
                        'sisa_tagihan' => $sisaTagihan,
                        'status' => $status,

                        // urutan prioritas
                        // pakai priority karena $studentsSummary adalah collection, bukan query db
                        'priority' => match ($status) {
                            'Ditolak' => 1,
                            'Menunggu Konfirmasi' => 2,
                            'Menunggak' => 3,
                            'Belum Bayar' => 4,
                            'Lunas' => 5,
                            'Tanpa tagihan' => 6,
                            default => 99,
                        }
                    ];
                })
                ->sortBy([
                    ['priority', 'asc'],
                    ['sisa_tagihan', 'desc']
                ])
                ->values();

                $page = request()->get('summary_page', 1);

                $perPage = 10;

                $studentsSummary = new LengthAwarePaginator(

                    $studentsSummary->forPage($page, $perPage),

                    $studentsSummary->count(),

                    $perPage,

                    $page,

                    [
                        'path' => request()->url(),

                        'pageName' => 'summary_page',

                        'query' => request()->query()
                    ]
                );
        }

        $pagePayments = collect($payments->items());

        $totalTagihan = $pagePayments
            ->sum('original_amount');

        $totalPaid = $pagePayments
            ->where('status', 'paid')
            ->sum('original_amount');

        $totalUnpaid = max(
            0,
            $totalTagihan - $totalPaid
        );
            
        $allPayments = Payment::where('type', 'monthly')->get();

        $totalTagihanAll = $allPayments->sum('original_amount');
        $totalDibayarAll = $allPayments
            ->where('status', 'paid')
            ->sum('original_amount');

        $sisaTagihanAll = $totalTagihanAll - $totalDibayarAll;

        return view('payments.index', compact(
            'payments',
            'totalUnpaid',
            'totalPaid',
            'students',
            'selectedStudent',
            'studentsSummary',
            'totalTagihanAll',
            'totalDibayarAll',
            'sisaTagihanAll',
        ));
    }

 
    public function create(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $fee = Fee::first();

        $studentId = $request->student_id;

        // ambil data siswa
        $student = Student::findOrFail($studentId);

        // proteksi akses orang tua
        if (
            $user->hasRole('orang_tua')
            && $student->parent_id != $user->id
        ) {
            abort(403);
        }

        // cari tagihan yang belum dibayar + belum upload
        $payment = Payment::where('student_id', $studentId)
            ->where('type', 'monthly')
            ->where(function ($q) {
                $q->whereNull('proof_file')
                    ->orWhere('status', 'rejected');
            })
            ->orderBy('month', 'asc')
            ->first();

        // handle null
        if (!$payment) {
            return back()->with(
                'error',
                'Tidak ada tagihan yang harus dibayarkan!'
            );
        }

        return view(
            'payments.create',
            compact('payment', 'fee')
        );
    }


    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        /** @var User $user */
        $user = Auth::user();

        // ambil payment
        $payment = Payment::with('student')->findOrFail($request->payment_id);

        // pastikan milik orang tua yg login
        if ($user->hasRole('orang_tua') && $payment->student->parent_id != $user->id) {
            abort(403, 'Tidak diizinkan');
        }

        // upload file bukti pembayaran
        $path = $request->file('proof_file')->store('payments', 'public');

        // hapus file lama (kalau ada)
        if ($payment->proof_file) {
            Storage::disk('public')->delete($payment->proof_file);
        }

        // update payment (FIFO)
        $payment->update([
            'proof_file' => $path,
            'status' => 'pending', // menunggu approve admin
            'paid_at' => now(),
        ]);

        // Kirim notifikasi ke superadmin
        $superadmins = User::role('superadmin')->get();

        foreach ($superadmins as $admin) {
            $admin->notify(
                new PaymentUploadedNotification($payment)
            );
        }

        return redirect()
            ->route('payments.index', [
                'student_id' => $payment->student_id
            ])->with(
                'success',
                'Bayar iuran berhasil! Menunggu verifikasi admin.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function showStudentPayments(Student $student)
    {
        /** @var User $user */
        $user = Auth::user();

        // proteksi orang tua
        if (
            $user->hasRole('orang_tua')
            && $student->parent_id != $user->id
        ) {
            abort(403);
        }

        // eager load relasi
        $student->load([
            'parent',
            'classroom'
        ]);

        // ambil iuran bulanan
        $payments = $student->payments()
            ->where('type', 'monthly')
            ->latest('month')
            ->paginate(10)
            ->withQueryString();

        // ambil collection page sekarang
        $pagePayments = $payments->getCollection();

        // total tagihan
        $totalTagihan = $pagePayments
            ->sum('original_amount');

        // total dibayar
        $totalPaid = $pagePayments
            ->where('status', 'paid')
            ->sum('original_amount');

        // sisa tagihan
        $totalUnpaid = max(
            0,
            $totalTagihan - $totalPaid
        );

        return view('payments.show', compact(
            'student',
            'payments',
            'totalUnpaid',
            'totalPaid'
        ));
    }


    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'original_amount' => 'required|integer|min:0'
        ]);

        $payment->update([
            'original_amount' => $request->original_amount
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Nominal berhasil diupdate');
    }


    // Iuran bulanan disetujui
    public function approve(Payment $payment)
    {
        if (!Auth::user()->hasRole('superadmin')) {
            abort(403);
        }

        $payment->load([
            'student.parent',
            'student.user'
        ]);

        // kalau sudah lunas
        if ($payment->status == 'paid') {
            return back()->with('error', 'Sudah lunas');
        }

        // approve registration
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
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            return back()->with('success', 'Siswa berhasil di-approve');
        }

        // approve iuran bulanan
        if ($payment->type == 'monthly') {

            $payment->update([
                'status' => 'paid',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            // kirim notifikasi ke orang tua siswa
            $parent = $payment->student->parent;

            if ($parent) {
                $parent->notify(
                    new PaymentApprovedNotification($payment)
                );
            }

            return back()->with('success', 'Pembayaran iuran disetujui!');
        }

        return back()->with('error', 'Pembayaran iuran tidak valid!');
    }


    // batalkan approve iuran bulanan (unapprove)
    public function unapprove(Payment $payment)
    {
        if (!Auth::user()->hasRole('superadmin')) {
            abort(403);
        }

        $payment->update([
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null
        ]);

        return back()->with('success', 'Pembayaran dibatalkan');
    }


    // Iuran bulanan ditolak
    public function reject(Request $request, Payment $payment)
    {
        if (!Auth::user()->hasRole('superadmin')) {
            abort(403);
        }

        $request->validate([
            'reject_reason' => 'required|string|max:255'
        ]);

        $payment->load('student.parent');

        if ($payment->status == 'paid') {
            return back()->with('error', 'Tidak bisa reject, sudah lunas');
        }

        // proof_file tetap
        $payment->update([
            'status' => 'rejected',
            'reject_reason' => $request->reject_reason ?? 'Bukti tidak valid'
        ]);

        // kirim notifikasi ke orang tua
        $parent = $payment->student->parent;

        if ($parent) {
            $parent->notify(
                new PaymentRejectedNotification($payment)
            );
        }

        return back()->with(
            'success',
            'Pembayaran iuran ditolak! Notifikasi telah dikirim ke orang tua siswa.'
        );
    }
}
