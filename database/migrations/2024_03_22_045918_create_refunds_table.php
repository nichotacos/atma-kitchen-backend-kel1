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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id('id_refund');
            $table->foreignId('id_status')->references('id_status')->on('statuses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_customer')->references('id_customer')->on('customers')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_bank_tujuan');
            $table->string('no_rekening_tujuan');
            $table->double('nominal_refund');
            $table->date('tanggal_refund');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
