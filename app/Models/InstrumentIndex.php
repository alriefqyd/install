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
}
