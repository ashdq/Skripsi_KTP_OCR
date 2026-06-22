<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE surats MODIFY status ENUM('menunggu', 'ditolak', 'diproses', 'selesai') DEFAULT 'menunggu'");
    }

    public function down(): void
    {
        // Reverting this might cause data loss if there are 'ditolak' rows, so just change it back to the original ENUM.
        // We'd have to update 'ditolak' to something else first if we wanted to be safe, but for a simple down migration:
        DB::statement("ALTER TABLE surats MODIFY status ENUM('menunggu', 'diproses', 'selesai') DEFAULT 'menunggu'");
    }
};
