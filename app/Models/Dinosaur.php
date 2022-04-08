<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dinosaur extends Model
{
    use HasFactory;

    protected $fillable = [
        'dinosaur_name_en',
        'dinosaur_name_vn',
        'dinosaur_name_spelling',
        'dinosaur_name_explain',
        'length',
        'weight',
        'lived',
        'author',
        'description_en',
        'description_vn',
        'food',
        'teeth',
        'how_it_move',
        'diet_id',
        'category_id',
        'collection',
        'image',
        'audio',
        'user_id',
        'dinosaur_id',
        'tmp_record',
    ];

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

    public function  posts(){
        return $this->belongsToMany(Post::class,PostDinosaur::class,'dinosaur_id','post_id');
    }

    public function users(){
        return $this->belongsToMany(User::class,UserDinosaur::class,'dinosaur_id','user_id');
    }

    public function owner(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function updateForDinosaur(){
        return $this->belongsTo(Dinosaur::class,'dinosaur_id','id');
    }
}
