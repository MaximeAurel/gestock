<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = ['nom','telephone','email','adresse'];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }

    public function achats()
    {
        return $this->hasMany(Achat::class);
    }
}
