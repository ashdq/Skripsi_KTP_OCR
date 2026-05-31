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
        Schema::table('dokumen', function (Blueprint $table) {
            if (Schema::hasColumn('dokumen', 'file_ktp')) {
                $table->dropColumn('file_ktp');
            }

            if (Schema::hasColumn('dokumen', 'file_kk')) {
                $table->dropColumn('file_kk');
            }

            if (Schema::hasColumn('dokumen', 'tgl_upload')) {
                $table->dropColumn('tgl_upload');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->string('file_ktp')->nullable();
            $table->string('file_kk')->nullable();
            $table->timestamp('tgl_upload')->nullable();
        });
    }
};