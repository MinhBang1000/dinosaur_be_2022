<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            "id"=> $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "born" => $this->born,
            "gender" => $this->gender,
            "avatar" => $this->avatar,
            "role" => $this->role,
            "liked" => StandardResource::collection($this->dinosaurs),  
            "posts" => PostResourceForUser::collection($this->posts),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
