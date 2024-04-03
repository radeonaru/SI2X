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
        Schema::create('umkm', function (Blueprint $table) {
            $table->id('id_umkm');
            $table->string('no_rw', 4);
            $table->string('nik_pemilik', 16);
            $table->string('nama_umkm', 50);
            $table->text('foto_umkm');
            $table->text('desc_umkm');
            $table->string('status_umkm', 50);
            $table->timestamps();

            // $table->foreign('nik_pemilik')->references('nik')->on('penduduk')->onDelete('cascade');
            // $table->foreign('no_rw')->references('no_rw')->on('rw')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('umkm');
    }
};