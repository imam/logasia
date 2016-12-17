<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class VehiclesModel extends Model
{
    public function date(){
        return $this->belongsTo('App\Date');
    }
}
