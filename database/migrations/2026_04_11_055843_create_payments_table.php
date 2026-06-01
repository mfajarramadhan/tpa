<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['registration', 'monthly']); // jenis pembayaran
            $table->string('month', 7)->nullable(); // format: YYYY-MM
            $table->integer('original_amount'); // nominal awal
            $table->integer('adjustment')->default(0); // penyesuaian (+/-)
            $table->integer('amount'); // total akhir
            $table->string('proof_file', 255)->nullable(); // path bukti
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('reject_reason')->nullable();
            // 1 siswa hanya 1 tipe pembayaran per bulan
            $table->unique(['student_id', 'type', 'month']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
