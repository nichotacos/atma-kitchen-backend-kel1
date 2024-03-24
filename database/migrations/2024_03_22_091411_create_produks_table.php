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
            $table->foreignId('id_ukuran')->references('id_ukuran')->on('ukuran_produks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_kategori')->references('id_kategori')->on('kategoris')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_kemasan')->references('id_kemasan')->on('kemasans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_penitip')->references('id_penitip')->on('penitips')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_produk');
            $table->string('deskripsi_produk');
            $table->double('harga_produk');
            $table->integer('stok');
            $table->integer('kuota_harian');
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
