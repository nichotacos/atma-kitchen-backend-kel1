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
            $table->foreignId('id_jenis_ketersediaan')->references('id_jenis_ketersediaan')->on('jenis_ketersediaans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_ukuran_produk')->references('id_ukuran_produk')->on('ukuran_produks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_kemasan')->references('id_kemasan')->on('kemasans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_produk');
            $table->string('deskripsi_produk');
            $table->string('kategori_produk');
            $table->double('harga_produk');
            $table->integer('ukuran_produk');
            $table->string('password_karyawan');
            $table->integer('stok');
            $table->string('kuota_harian');
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
