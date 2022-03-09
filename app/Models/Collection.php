<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    use HasFactory;

    public function dinosaur(){
        return $this->belongsTo(Dinosaur::class,'dinosaur_id','id');
    }
}
