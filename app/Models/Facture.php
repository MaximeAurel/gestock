<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero','client_id','date_facture','total_ht','total_tva','total_ttc','montant_paye','reste_a_payer','solde','statut'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lignes()
    {
        return $this->hasMany(LigneFacture::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
