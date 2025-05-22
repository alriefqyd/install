<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public $guarded = ['id'];
    public function areas()
    {
        return $this->hasOne(Area::class,'id','parent_id');
    }

    public function services(){
        return $this->hasMany(Service::class,'area_id','id');
    }


}
