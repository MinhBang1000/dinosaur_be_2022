<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function dinosaursAll(){
        return $this->belongsToMany(Dinosaur::class,DinosaurCountry::class,'country_id','dinosaur_id');
    }

    public function dinosaurs(){
        return $this->dinosaursAll()->where('decision',1);
    }
}
