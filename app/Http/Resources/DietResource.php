<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DietResource extends JsonResource
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
            "id" => $this->id,
            "diet_name_en" => $this->diet_name_en,
            "diet_name_vn" => $this->diet_name_vn,
            "diet_charater_en" => $this->diet_charater_en,
            "diet_charater_vn" => $this->diet_charater_vn,
            "diet_icon" => $this->diet_icon,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "dinosaurs" => DinosaurOnlyNameResource::collection($this->dinosaurs),
        ];
    }
}
