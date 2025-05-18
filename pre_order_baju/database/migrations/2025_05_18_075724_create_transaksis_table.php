<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('telepon');
            $table->string('email');
            $table->text('alamat');
            $table->string('metode_pembayaran');
            $table->string('va_number')->nullable(); // nomor virtual account
            $table->timestamp('expired_at')->nullable(); // masa expired
            $table->integer('total'); // total transaksi
            $table->json('items'); // list barang dalam bentuk JSON
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};