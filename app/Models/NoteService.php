<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoteService extends Model
{
    protected $table = 'note_services';
    protected $fillable = [
        'titre',
        'contenu',
        'date_publication',
        'auteur',
        'fichier',
    ];
}
