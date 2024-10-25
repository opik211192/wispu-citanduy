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
        Schema::create('identitas_diris', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengaduan_id')->nullable();
            $table->string('nama_identitas')->nullable();
            $table->string('alamat_identitas')->nullable();
            $table->string('email_identitas')->nullable();
            $table->string('no_telpon_identitas')->nullable();
            $table->timestamps();

            $table->foreign('pengaduan_id')->references('id')->on('pengaduans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identitas_diris');
    }
};
