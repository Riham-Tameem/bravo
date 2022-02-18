<?php


namespace App\Support;

use App\Models\Language;
use Illuminate\Support\Facades\Config;

trait Translateable
{
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($model) {

            //Let's get our supported configurations from the config file we've created
            $languages = getLanguages();
            foreach ($languages as $language) {
                if (!$model->translationAllLang()->where('language', $language)->first()) //if  translated dont make translate again
                    $model->translationModel()->create(['language' => $language]);
            }


        });
        static::deleting(function ($obj) {
            $obj->translationAllLang()->delete();
        });

    }
}
