<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PDO;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['id','category_name_en','category_name_vn','category_description_en','category_description_vn'];

    public function dinosaurs(){
        return $this->hasMany(Dinosaur::class,'category_id','id');
    }
}
