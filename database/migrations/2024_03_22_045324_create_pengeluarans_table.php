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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->foreignId('id_jenis_pengeluaran')->references('id_jenis_pengeluaran')->on('jenis_pengeluarans')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal_pengeluaran');
            $table->double('nominal_pengeluaran');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluarans');
    }
};
