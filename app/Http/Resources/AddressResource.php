<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'id'    =>   $this->id,
            'type'  => $this->type,
            'address'  => $this->address,
            'latitude'  => $this->latitude,
            'longitude'  => $this->longitude,
            'user' => new UserResource($this->user),
            ];
    }
}
