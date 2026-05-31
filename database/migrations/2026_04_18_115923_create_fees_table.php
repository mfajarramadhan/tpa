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
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->integer('registration_fee')->default(100000);
            $table->integer('monthly_fee')->default(50000);
            $table->string('bank_name', 100)->default('BCA');
            $table->string('account_name', 100)->default('YAYASAN AL-BAROKAH');
            $table->string('account_number', 20)->default('1234567890');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fees', function (Blueprint $table) {

            $table->dropColumn([
                'bank_name',
                'account_name',
                'account_number'
            ]);

        });
    }
};
