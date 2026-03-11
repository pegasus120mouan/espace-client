<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commande extends Model
{
    protected $table = 'commandes';

    public $timestamps = false;

    protected $fillable = [
        'utilisateur_id',
        'livreur_id',
        'communes',
        'cout_livraison',
        'cout_reel',
        'cout_global',
        'statut',
        'point_valide',
        'date_validation_point',
        'paiement_effectue',
        'operateur_paiement',
        'date_paiement',
        'date_reception',
        'date_livraison',
        'date_retour',
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function livreur(): BelongsTo
    {
        return $this->belongsTo(Utilisateur::class, 'livreur_id');
    }
}
