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
        Schema::create('hampers', function (Blueprint $table) {
            $table->id('id_hampers');
            $table->foreignId('id_kemasan')->references('id_kemasan')->on('kemasans')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nama_hampers');
            $table->double('harga_hampers');
            $table->string('gambar_hampers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hampers');
    }
};
