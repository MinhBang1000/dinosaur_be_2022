<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesozoic extends Model
{
    use HasFactory;

    public function dinosaurs(){
        return $this->belongsToMany(Dinosaur::class,DinosaurMesozoic::class,'mesozoic_id','dinosaur_id');
    }
}
