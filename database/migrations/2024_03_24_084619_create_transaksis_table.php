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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id('id_transaksi');
            $table->string('nomor_nota');
            $table->foreignId('id_customer')->references('id_customer')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_pengambilan')->references('id_pengambilan')->on('jenis_pengambilans')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_cart')->references('id_cart')->on('carts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_status')->references('id_status')->on('statuses')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_alamat')->nullable()->references('id_alamat')->on('alamats')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('tanggal_pemesanan');
            $table->dateTime('tanggal_pelunasan')->nullable();
            $table->dateTime('tanggal_ambil')->nullable();
            $table->double('total_harga_produk');
            $table->double('jarak_pengiriman');
            $table->double('ongkos_kirim');
            $table->double('total_setelah_ongkir');
            $table->integer('poin_sebelumnya');
            $table->integer('poin_digunakan');
            $table->double('total_harga_final');
            $table->integer('perolehan_poin');
            $table->double('nominal_tip');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
