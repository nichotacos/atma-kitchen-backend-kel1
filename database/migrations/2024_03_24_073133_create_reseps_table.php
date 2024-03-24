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
        Schema::create('reseps', function (Blueprint $table) {
            $table->foreignId('id_detail_resep')->references('id_detail_resep')->on('detail_reseps')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_produk')->references('id_produk')->on('produks')->onUpdate('cascade')->onDelete('cascade');

            $table->primary(['id_detail_resep', 'id_produk']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};
