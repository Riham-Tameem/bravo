<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }
   /* public function productTranslation()
    {
        $lang = app()->getLocale();

        if (request()->hasHeader('lang')){
            $lang = request()->header('lang');
        }

        return $this->hasOne(ProductTranslation::class,'product_id','id')->where('product_translations.language',$lang)->first();
    }*/
    public function productTranslations()
    {
        return $this->hasMany(ProductTranslation::class);
    }
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }
    public function favorites()
    {
        return $this->belongsToMany(User::class,'favorites','product_id','user_id','id','id');//->whereNull('favorites.deleted_at');
    }
    function orders(){
        return $this->belongsToMany(Order::class,'order_products');
    }
    public function translation($language = null)
    {
        if ($language == null) {
            $language = app()->getLocale();
        }
        return $this->hasMany(ProductTranslation::class)->where('language', '=', $language)->first();
    }

    public function translationModel($language = null)
    {
        if ($language == null) {
            $language = app()->getLocale();
        }
        return $this->hasMany(ProductTranslation::class)->where('language', '=', $language);
    }

//to get all translation for model
    public function translationAllLang()
    {
        return $this->hasMany(ProductTranslation::class);
    }
}
