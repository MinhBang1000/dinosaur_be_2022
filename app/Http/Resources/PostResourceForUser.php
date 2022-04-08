<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResourceForUser extends JsonResource
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
            'post_avatar' => $this->post_avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dinosaurs' => DinosaurOnlyNameResource::collection($this->dinosaurs)
        ];
    }
}
