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
        Schema::create('fee_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fee_id')->constrained()->cascadeOnDelete();
            $table->integer('old_registration_fee');
            $table->integer('new_registration_fee');
            $table->integer('old_monthly_fee');
            $table->integer('new_monthly_fee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_logs', function (Blueprint $table) {
            $table->dropForeign(['fee_id']);
            $table->dropColumn('fee_id');
        });
    }
};
