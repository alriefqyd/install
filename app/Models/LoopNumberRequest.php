<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoopNumberRequest extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'loop_number' => 'array',
    ];

    public function engineers(){
        return $this->hasOne(Engineers::class , 'id' , 'engineers_id');
    }

    public function areas(){
        return $this->hasOne(Area::class , 'id' , 'area_id');
    }

    public function services(){
        return $this->hasOne(Service::class , 'id' , 'services_id');
    }
}
