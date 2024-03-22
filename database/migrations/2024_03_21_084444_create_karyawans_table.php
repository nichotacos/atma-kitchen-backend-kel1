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
        Schema::create('karyawans', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->foreignId('id_role')->references('id_role')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_karyawan');
            $table->string('nomor_telepon_karyawan')->unique();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->date('tanggal_rekrut');
            $table->double('gaji_harian');
            $table->double('bonus_rajin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawans');
    }
};
