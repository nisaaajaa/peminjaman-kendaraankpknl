<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->date('tgl_pinjam')->nullable()->after('masa_pinjam');
            $table->date('tgl_kembali')->nullable()->after('tgl_pinjam');
            $table->string('seksi')->nullable()->after('keperluan');
            $table->index('status');
            
            // In SQLite, altering foreign keys is tricky. We'll skip altering the foreign key 
            // constraint directly in this migration to avoid SQLite table rebuild issues 
            // and just rely on the new columns for now.
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->index('nip');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex(['nip']);
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn(['tgl_pinjam', 'tgl_kembali', 'seksi']);
        });
    }
};
