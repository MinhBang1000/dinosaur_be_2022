<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'post_title' => $this->post_title,
            'post_content' => $this->post_content,
            'post_avatar' => $this->post_avatar,
            'user' => new UserResourceForPost(User::find($this->user_id)),
            'comments' => CommentResource::collection($this->commentsNotChild),
            'users' => UserResourceOnlyName::collection($this->users),
            'post_decision' => $this->post_decision==0?false:true,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dinosaurs' => DinosaurOnlyNameResource::collection($this->dinosaurs)
        ];
    }
}
