<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id(); // kolom primary key 'id'
            $table->string('tipe'); // pemasukan / pengeluaran
            $table->integer('jumlah');
            $table->string('metode');
            $table->string('status')->default('valid');
            $table->date('tanggal');
            $table->text('deskripsi')->nullable();
            $table->timestamps(); // created_at dan updated_at (opsional tapi disarankan)
        });
    }

    public function down(): void {
        Schema::dropIfExists('pembayaran');
    }
};
