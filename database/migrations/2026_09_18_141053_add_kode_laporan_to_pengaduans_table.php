<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('pengaduans', function (Blueprint $table) {
        $table->string('kode_laporan', 20)->nullable()->unique()->after('nomor_laporan');
    });
}

public function down()
{
    Schema::table('pengaduans', function (Blueprint $table) {
        $table->dropColumn('kode_laporan');
    });
}
};
