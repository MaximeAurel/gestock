<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'designation','code_barre','categorie_id','fournisseur_id','unite_id',
        'prix_achat','prix_vente','stock_min','image','description','statut'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class);
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class);
    }

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }
}
