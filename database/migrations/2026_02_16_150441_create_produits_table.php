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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->string('code_barre')->unique()->nullable();
            $table->foreignId('categorie_id')->constrained()->cascadeOnDelete();
            $table->foreignId('fournisseur_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unite_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('prix_achat',12,2)->default(0);
            $table->decimal('prix_vente',12,2);
            $table->integer('stock_min')->default(0);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('statut')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
