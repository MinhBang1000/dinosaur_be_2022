<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diet extends Model
{
    use HasFactory;

    public function dinosaursAll(){
        return $this->hasMany(Dinosaur::class,'diet_id','id');
    }

    public function dinosaurs(){
        return $this->dinosaursAll()->where('decision',1);
    }
}
