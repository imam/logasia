<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PupTrailer extends VehiclesModel
{
    public $totalInventory = 5;

    public $vehiclesAvailableValidationRules;

    public function vehiclesAvailableValidationRules()
    {
        return 'required|integer|min:0|max:'.$this->totalInventory;
    }

    public function getTotalInventoryAttribute()
    {
        return 5;
    }
}
