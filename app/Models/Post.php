<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'post_title',
        'post_content',
        'post_avatar',
        'user_id',
        'post_decision'
    ];

    protected function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    protected function dinosaursAll(){
        return $this->belongsToMany(Dinosaur::class,PostDinosaur::class,'post_id','dinosaur_id');
    }

    protected function dinosaurs(){
        return $this->dinosaursAll()->where('decision',1);
    }

    protected function comments(){
        return $this->hasMany(Comment::class,'post_id','id');
    }

    protected function commentsNotChild(){
        return $this->comments()->where('child_of_comment',0);
    }

    protected function users(){
        return $this->belongsToMany(User::class,UserPost::class,'post_id','user_id');
    }
}
