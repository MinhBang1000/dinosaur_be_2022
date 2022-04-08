<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPost extends Model
{
    use HasFactory;
    protected $fillable = ['id','user_id','post_id','created_at','updated_at'];
}
