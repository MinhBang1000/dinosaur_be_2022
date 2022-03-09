<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MesozoicResource extends JsonResource
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
            'mesozoic_name_en' => $this->mesozoic_name_en,
            'mesozoic_name_vn' => $this->mesozoic_name_vn,
            'mesozoic_start' => $this->mesozoic_start,
            'mesozoic_end' => $this->mesozoic_end,
            'mesozoic_earth' => $this->mesozoic_earth,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'dinosaurs' => DinosaurOnlyNameResource::collection($this->dinosaurs)
        ];
    }
}
