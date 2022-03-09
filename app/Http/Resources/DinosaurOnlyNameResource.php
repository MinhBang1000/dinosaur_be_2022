<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DinosaurOnlyNameResource extends JsonResource
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
            'dinosaur_name_en' => $this->dinosaur_name_en,
        ];
    }
}
