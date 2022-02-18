<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'    =>   $this->id,
            'status' => $this->status ?? 'pending',
            'sub_total' => $this->sub_total,
            'result' => $this->result,
            'delivery' => $this->delivery,
            'products' =>  ProductResource::collection($this->products),
            'address' => $this->address,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
          //  'user' =>$this->user
        ];
    }
}
