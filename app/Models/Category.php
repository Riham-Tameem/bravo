<?php

namespace App\Models;

use App\Support\Translateable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use Translateable;
    protected $guarded = [];
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function categoryTranslations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }
   /* public function categoryTranslation()
    {
        $lang = app()->getLocale();

        if (request()->hasHeader('lang')){
            $lang = request()->header('lang');
        }

        return $this->hasOne(CategoryTranslation::class,'category_id','id')->where('category_translations.language',$lang)->first();
    }*/
    public function translation($language = null)
    {
        if ($language == null) {
            $language = app()->getLocale();
        }
        return $this->hasMany(CategoryTranslation::class)->where('language', '=', $language)->first();
    }

    public function translationModel($language = null)
    {
        if ($language == null) {
            $language = app()->getLocale();
        }
        return $this->hasMany(CategoryTranslation::class)->where('language', '=', $language);
    }

//to get all translation for model
    public function translationAllLang()
    {
        return $this->hasMany(CategoryTranslation::class);
    }
}
