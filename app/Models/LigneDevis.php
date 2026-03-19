<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneDevis extends Model
{
    use HasFactory;

    protected $table = 'ligne_devis';

    protected $fillable = [
        'devis_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'tva',
        'total'
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
