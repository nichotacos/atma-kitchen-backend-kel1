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
        Schema::create('detail_reseps', function (Blueprint $table) {
            $table->id('id_detail_resep');
            $table->foreignId('id_bahan_baku')->references('id_bahan_baku')->on('bahan_bakus')->onUpdate('cascade')->onDelete('cascade');
            $table->double('jumlah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_reseps');
    }
};
