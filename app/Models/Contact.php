<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'email',
        'sujet',
        'sujet_en',
        'message',
        'message_en',
        'est_lu',
        'reponse',
    ];
}
