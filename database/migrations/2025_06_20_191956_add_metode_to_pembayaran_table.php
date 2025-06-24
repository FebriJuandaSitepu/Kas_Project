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
        Schema::table('pembayaran', function (Blueprint $table) {
            // Menghindari error jika kolom sudah ada
            if (!Schema::hasColumn('pembayaran', 'metode')) {
                $table->string('metode', 50); // tanpa after('jumlah')
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran', 'metode')) {
                $table->dropColumn('metode');
            }
        });
    }
};
