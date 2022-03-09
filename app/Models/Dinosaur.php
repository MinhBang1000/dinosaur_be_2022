<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinosaur extends Model
{
    use HasFactory;

    public function diet(){
        return $this->belongsTo(Diet::class,'diet_id','id');
    }

    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function mesozoics(){
        return $this->belongsToMany(Mesozoic::class,DinosaurMesozoic::class,'dinosaur_id','mesozoic_id');
    }

    public function countries(){
        return $this->belongsToMany(Country::class,DinosaurCountry::class,'dinosaur_id','country_id');
    }

    public function images(){
        return $this->hasMany(Image::class,'dinosaur_id','id');
    }
}
