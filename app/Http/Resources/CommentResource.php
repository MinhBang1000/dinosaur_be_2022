<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'comment_title' => $this->comment_title,
            'comment_content' => $this->comment_content,
            'post' => new PostResourceOnlyName($this->post),
            'user' => new UserResource($this->user),
            'users' => UserResourceOnlyName::collection($this->users),
            'comments' => CommentResource::collection($this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
