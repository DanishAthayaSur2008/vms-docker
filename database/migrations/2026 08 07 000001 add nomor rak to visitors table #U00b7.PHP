<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            // bukan unique: satu orang punya banyak baris (tiap kunjungan 1 baris)
            $table->string('visitor_id', 5)->nullable()->index()->after('no_hp');
        });

        // Backfill data lama: 1 ID unik untuk setiap nomor HP
        $used = [];

        DB::table('visitors')
            ->whereNull('visitor_id')
            ->distinct()
            ->pluck('no_hp')
            ->each(function ($noHp) use (&$used) {
                do {
                    $id = (string) random_int(10000, 99999);
                } while (isset($used[$id]));

                $used[$id] = true;

                DB::table('visitors')->where('no_hp', $noHp)->update(['visitor_id' => $id]);
            });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex(['visitor_id']);
            $table->dropColumn('visitor_id');
        });
    }
};