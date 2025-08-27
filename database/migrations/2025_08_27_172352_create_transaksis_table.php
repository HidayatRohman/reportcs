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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_tlp');
            $table->foreignId('produk_id')->constrained('produks');
            $table->enum('chat', ['Baru', 'Followup']);
            $table->string('provinsi');
            $table->string('kota');
            $table->decimal('transaksi', 15, 2);
            $table->enum('keterangan', ['Aktif', 'Off Sementara', 'Otw Open', 'Tutup', 'Lainnya']);
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
