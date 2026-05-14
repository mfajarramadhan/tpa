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
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('classroom_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();

            $table->string('nisn', 10)->unique();
            $table->string('name', 100); 
            $table->date('birth_date');

            $table->string('school_origin', 50); 
            $table->string('school_grade', 20);
            $table->enum('gender', ['L', 'P']);

            $table->string('kk_file', 255)->nullable(); // path file
            $table->string('birth_certificate_file', 255)->nullable(); // path file

            $table->enum('status', ['aktif', 'nonaktif', 'ditolak', 'alumni'])->default('aktif');

            $table->text('reject_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
