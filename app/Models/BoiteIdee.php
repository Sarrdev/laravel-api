<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoiteIdee extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_complet',
        'idee',
        'statut',
    ];
}
