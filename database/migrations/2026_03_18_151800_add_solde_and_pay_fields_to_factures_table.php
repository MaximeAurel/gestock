<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            if (!Schema::hasColumn('factures', 'montant_paye')) {
                $table->decimal('montant_paye', 12, 2)->default(0)->after('total_ttc');
            }
            if (!Schema::hasColumn('factures', 'reste_a_payer')) {
                $table->decimal('reste_a_payer', 12, 2)->default(0)->after('montant_paye');
            }
            if (!Schema::hasColumn('factures', 'solde')) {
                $table->decimal('solde', 12, 2)->default(0)->after('reste_a_payer');
            }
        });

        // Initial backfill: reste_a_payer & solde = total_ttc, montant_paye = 0
        DB::table('factures')->update([
            'montant_paye' => DB::raw('COALESCE(montant_paye,0)'),
            'reste_a_payer' => DB::raw('COALESCE(total_ttc,0)'),
            'solde' => DB::raw('COALESCE(total_ttc,0)')
        ]);
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            if (Schema::hasColumn('factures', 'solde')) {
                $table->dropColumn('solde');
            }
            if (Schema::hasColumn('factures', 'reste_a_payer')) {
                $table->dropColumn('reste_a_payer');
            }
            if (Schema::hasColumn('factures', 'montant_paye')) {
                $table->dropColumn('montant_paye');
            }
        });
    }
};
