<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['id','comment_title','comment_content','child_of_comment','user_id','post_id','created_at','updated_at'];

    public function post(){
        return $this->belongsTo(Post::class,'post_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function comments(){
        return $this->hasMany(Comment::class,'child_of_comment','id');
    }
    public function users(){
        return $this->belongsToMany(User::class,UserComment::class,'comment_id','user_id');
    }
}
