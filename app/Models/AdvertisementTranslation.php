<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementTranslation extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

}
