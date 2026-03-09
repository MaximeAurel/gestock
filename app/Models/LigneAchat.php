<?php

namespace App\Models;

use App\Models\Achat;
use App\Models\Produit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LigneAchat extends Model
{
        use HasFactory;

    protected $fillable = ['achat_id','produit_id','quantite','prix','total'];

    public function achat()
    {
        return $this->belongsTo(Achat::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

}
