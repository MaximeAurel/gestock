<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unite extends Model
{
    use HasFactory;

    protected $fillable = ['nom','abr'];

    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
