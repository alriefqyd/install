<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstrumentIndex extends Model
{
    protected $guarded = ['id'];

    public function loopNumberRequest()
    {
        return $this->hasOne(LoopNumberRequest::class,'loop_number_request_id','id');
    }

    public function areas(){
        return $this->hasOne(Area::class,'id','area_id');
    }

    public function services(){
        return $this->hasOne(Service::class,'id','service_id');
    }

}
