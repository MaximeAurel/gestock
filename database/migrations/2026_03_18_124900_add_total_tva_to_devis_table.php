<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            if (!Schema::hasColumn('devis', 'total_tva')) {
                $table->decimal('total_tva', 12, 2)->default(0)->after('total_ht');
            }
        });
    }

    public function down(): void
    {
        Schema::table('devis', function (Blueprint $table) {
            if (Schema::hasColumn('devis', 'total_tva')) {
                $table->dropColumn('total_tva');
            }
        });
    }
};
