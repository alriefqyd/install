<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = ['id'];

    public function areas(){
        return $this->hasOne(Area::class,'id','area_id');
    }

}
