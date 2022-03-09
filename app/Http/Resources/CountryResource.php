<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            'country_name' => $this->country_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dinosaurs' => DinosaurOnlyNameResource::collection($this->dinosaurs)
        ];
    }
}
