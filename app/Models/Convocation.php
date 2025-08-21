<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Convocation extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'date_reunion',
        'lieu',
        'statut',
    ];


}
