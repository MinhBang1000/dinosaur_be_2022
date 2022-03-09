<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'category_name_en' => $this->category_name_en,
            'category_name_vn' => $this->category_name_vn,
            'category_description_en' => $this->category_description_en,
            'category_description_vn' => $this->category_description_vn,
            'dinosaurs' => DinosaurOnlyNameResource::collection($this->dinosaurs)
        ];
    }
}
