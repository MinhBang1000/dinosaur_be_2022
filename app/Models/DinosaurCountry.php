<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinosaurCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'dinosaur_id',
        'country_id',
    ];
}
