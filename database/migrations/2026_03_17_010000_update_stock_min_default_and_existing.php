<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mettre à jour le défaut et les lignes existantes
        DB::table('produits')->where('stock_min', '<', 1)->update(['stock_min' => 1]);

        Schema::table('produits', function (Blueprint $table) {
            $table->integer('stock_min')->default(1)->change();
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->integer('stock_min')->default(0)->change();
        });
    }
};
