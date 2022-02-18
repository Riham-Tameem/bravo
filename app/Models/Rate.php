<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

   /* public function rating($product_id){
        $rate=Rate::where('product_id',$product_id)->pluck('rate')->toArray();
        $sum = array_sum($rate);
        $count = Rate::where('product_id',$product_id)->count();
        $avg =$sum / $count;
        return $avg;
    }*/
}
