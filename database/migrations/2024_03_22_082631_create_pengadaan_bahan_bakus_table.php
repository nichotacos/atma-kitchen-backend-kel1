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
        Schema::create('pengadaan_bahan_bakus', function (Blueprint $table) {
            $table->id('id_pengadaan');
            $table->foreignId('id_bahan_baku')->references('id_bahan_baku')->on('bahan_bakus')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_unit')->references('id_unit')->on('units')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('jumlah_pengadaan');
            $table->double('harga_per_unit');
            $table->double('harga_total');
            $table->datetime('tanggal_pengadaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengadaan_bahan_bakus');
    }
};
