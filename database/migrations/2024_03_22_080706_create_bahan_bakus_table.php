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
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id('id_bahan_baku');
            $table->foreignId('id_unit')->references('id_unit')->on('units')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama_bahan_baku');
            $table->double('stok_bahan_baku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
