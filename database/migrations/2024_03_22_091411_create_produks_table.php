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
        Schema::create('produks', function (Blueprint $table) {
            $table->id('id_produk');
            $table->string('nama_produk');
            $table->string('deskripsi_produk');
            $table->string('kategori_produk');
            $table->double('harga_produk');
            $table->integer('ukuran_produk');
            $table->string('password_karyawan');
            $table->integer('stok');
            $table->string('kuota_harian');
            $table->integer('id_penitip');
            $table->integer('id_pengambilan');
            $table->integer('id_kategori');
            $table->integer('id_ukuran');
            $table->integer('id_kemasan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
