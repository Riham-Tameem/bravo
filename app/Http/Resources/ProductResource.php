<?php

namespace App\Http\Resources;

use App\Models\Rate;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      //  return parent::toArray($request);
        $avg=0;
        $rate=Rate::where('product_id',$this->id)->pluck('rate')->toArray();
        $sum = array_sum($rate);
        $count = Rate::where('product_id',$this->id)->count();
        if($rate)
          $avg = ceil($sum / $count );

        $is_favorite = $this->favorites()->where('users.id',auth()->user()->id)->first();
        return [
            'id'    =>   $this->id,
            'name'  => $this->productTranslation()?$this->productTranslation()->name:"",
            'description'  => $this->productTranslation()?$this->productTranslation()->description:"",
            'main_price'  => $this->main_price,
            'current_price'  => $this->current_price,
            'main_image'  => $this->image,
            'is_favorite'  => $is_favorite?true:false,
            'category' => new CategoryResource($this->category),
            'images' => $this->productImages,
            'Rate' => $avg,
        ];
    }
}
