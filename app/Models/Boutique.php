<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Boutique extends Model
{
    protected $table = 'boutiques';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'adresse',
        'contact',
        'email',
        'logo',
        'statut',
    ];

    protected $casts = [
        'statut' => 'boolean',
    ];

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(Utilisateur::class, 'boutique_id');
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'boutique_id');
    }
}
