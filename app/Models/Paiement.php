<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['facture_id','date_paiement','montant','mode_paiement','reference'];

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }
}
