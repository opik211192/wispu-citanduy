<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom status_proses
     * untuk menyimpan status proses pengaduan.
     */
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('status_proses')
                  ->default('Menunggu')
                  ->after('status');
        });
    }

    /**
     * Menghapus kolom status_proses
     * jika migration di-rollback.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn('status_proses');
        });
    }
};