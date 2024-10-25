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
        Schema::create('uraian_pihaks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengaduan_id');
            $table->string('nama');
            $table->string('jabatan');
            $table->string('klasifikasi');
            $table->text('alamat');
            $table->string('no_telpon');
            $table->string('instansi');
            $table->string('paket_kegiatan');
            $table->string('peran');
            $table->timestamps();
            
            $table->foreign('pengaduan_id')->references('id')->on('pengaduans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uraian_pihaks');
    }
};
