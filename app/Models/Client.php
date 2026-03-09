<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['nom','telephone','email','adresse','credit'];

    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    public function devis()
    {
        return $this->hasMany(Devis::class);
    }

}
