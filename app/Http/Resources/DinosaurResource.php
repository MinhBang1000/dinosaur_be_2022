<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\PrettyPrinter\Standard;

class DinosaurResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $collections = [];
        $i = 1;
        for ($i=1;$i<=$this->collection;$i++){
            $collections[] = $this->dinosaur_name_en.'-'.$i.'.jpg';
        } 
        return [
            'id' => $this->id,
            'dinosaur_name_en' => $this->dinosaur_name_en,
            'dinosaur_name_vn' => $this->dinosaur_name_vn,
            'dinosaur_name_spelling' => $this->dinosaur_name_spelling,
            'dinosaur_name_explain' => $this->dinosaur_name_explain,
            'length' => $this->length,
            'lived' => $this->lived,
            'weight' => $this->weight,
            'teeth' => $this->teeth,
            'food' => $this->food,
            'how_it_move' => $this->how_it_move,
            'author' => $this->author,
            'audio' => $this->audio,
            'description_en' => $this->description_en,
            'description_vn' => $this->description_vn,
            'image' => $this->image,
            'collection' => $this->collection,
            'likers' => UserResourceOnlyName::collection($this->users),
            'owner' => new StandardResource($this->owner),
            'update_for' => new StandardResource($this->updateForDinosaur),
            'diet' => new DietResource($this->diet),
            'category' => new CategoryResource($this->category),
            'mesozoics' => MesozoicResource::collection($this->mesozoics),
            'countries' => CountryResource::collection($this->countries),
            'images' => $collections,
            'decision' => $this->decision==0?false:true,
            'tmp_record' => $this->tmp_record==0?false:true,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
