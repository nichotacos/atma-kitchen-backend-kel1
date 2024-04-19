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
        Schema::create('detail_carts', function (Blueprint $table) {
            $table->id('id_detail_cart');
            $table->foreignId('id_hampers')->nullable()->references('id_hampers')->on('hampers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_produk')->nullable()->references('id_produk')->on('produks')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_cart')->references('id_cart')->on('carts')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('jumlah_produk');
            $table->double('harga_produk_terkini');
            $table->double('harga_total_terkini');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_carts');
    }
};
