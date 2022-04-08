<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDinosaur extends Model
{
    use HasFactory;

    protected $fillable = [
        'id','dinosaur_id','user_id','created_at','updated_at'
    ];
}
