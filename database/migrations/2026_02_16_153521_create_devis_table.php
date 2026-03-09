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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->date('date_devis');
            $table->date('date_expiration')->nullable();
            $table->decimal('total_ht',12,2)->default(0);
            $table->decimal('total_ttc',12,2)->default(0);
            $table->string('statut')->default('brouillon'); // brouillon, approuvé, expiré
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devis');
    }
};
