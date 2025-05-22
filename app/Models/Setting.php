<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    const area = [
        'process_plant' => 'PROCESS PLANT',
        'mining' => 'MINING',
        'hydro' => 'HYDRO/UTILITIES',
        'others' => 'OTHERS',
    ];
}
