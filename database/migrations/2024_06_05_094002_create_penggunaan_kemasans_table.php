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
        Schema::create('penggunaan_kemasans', function (Blueprint $table) {
            $table->id('id_penggunaan_kemasan');
            $table->foreignId('id_kemasan')->references('id_kemasan')->on('kemasans')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('penggunaan_kemasans');
    }
};
