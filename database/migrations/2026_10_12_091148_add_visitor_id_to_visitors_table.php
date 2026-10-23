<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ID Visitor = 5 digit angka, dibuat otomatis oleh sistem saat
     * registrasi. Satu orang (satu nomor HP) memakai satu ID yang sama
     * di semua kunjungannya, jadi kolom ini TIDAK unique per baris.
     */
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('visitor_id', 5)->nullable()->index()->after('no_hp');
        });

        // Backfill data lama: satu ID per nomor HP.
        $used = [];

        foreach (DB::table('visitors')->select('no_hp')->distinct()->pluck('no_hp') as $noHp) {
            do {
                $id = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            } while (isset($used[$id]));

            $used[$id] = true;

            DB::table('visitors')->where('no_hp', $noHp)->update(['visitor_id' => $id]);
        }
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex(['visitor_id']);
            $table->dropColumn('visitor_id');
        });
    }
};
