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
        Schema::create('jenis_pengeluarans', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->string('tanggal_pengeluaran');
            $table->string('nominal_pengeluaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_pengeluarans');
    }
};
