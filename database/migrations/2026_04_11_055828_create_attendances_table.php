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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->date('date'); // tanggal absensi
            $table->enum('session', ['pagi', 'sore']);
            $table->foreignId('created_by')->constrained('users'); // guru/admin
            $table->timestamps();

            $table->unique(['classroom_id', 'date', 'session']); // 1 kelas 1 hari 1 absensi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
