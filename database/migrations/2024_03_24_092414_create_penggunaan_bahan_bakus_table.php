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
        Schema::create('penggunaan_bahan_bakus', function (Blueprint $table) {
            $table->id('id_penggunaan');
            $table->foreignId('id_unit')->references('id_unit')->on('units')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_bahan_baku')->references('id_bahan_baku')->on('bahan_bakus')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_transaksi')->references('id_transaksi')->on('transaksis')->onUpdate('cascade')->onDelete('cascade');
            $table->double('jumlah_penggunaan');
            $table->dateTime('tanggal_penggunaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan_bahan_bakus');
    }
};
