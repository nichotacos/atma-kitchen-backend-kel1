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
        Schema::create('penggajians', function (Blueprint $table) {
            $table->id('id_penggajian');
            $table->foreignId('id_karyawan')->references('id_karyawan')->on('karyawans')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('jumlah_hadir');
            $table->integer('jumlah_bolos');
            $table->double('bonus');
            $table->double('total_gaji');
            $table->date('tanggal_penggajian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggajians');
    }
};
