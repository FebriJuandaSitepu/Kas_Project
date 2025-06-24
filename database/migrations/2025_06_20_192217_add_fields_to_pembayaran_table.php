<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Jangan tambahkan user_id, status karena SUDAH ADA
            if (!Schema::hasColumn('pembayaran', 'metode')) {
                $table->string('metode')->nullable()->after('jumlah');
            }

            if (!Schema::hasColumn('pembayaran', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('metode');
            }

            if (!Schema::hasColumn('pembayaran', 'bukti_pembayaran')) {
                $table->string('bukti_pembayaran')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['metode', 'tanggal', 'bukti_pembayaran']);
        });
    }
};
