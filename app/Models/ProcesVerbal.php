<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesVerbal extends Model
{
    use HasFactory;

    protected $table = 'proce_verbals';

    protected $fillable = [
        'titre',
        'contenu',
        'date_reunion',
        'auteur',
        'fichier',
    ];
}

