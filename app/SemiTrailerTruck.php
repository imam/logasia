<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Watson\Validating\ValidatingTrait;

class SemiTrailerTruck extends VehiclesModel
{
    public $totalInventory = 3;

    public $vehiclesAvailableValidationRules;

    public function vehiclesAvailableValidationRules()
    {
        return 'required|integer|min:0|max:'.$this->totalInventory;
    }

    public function getTotalInventoryAttribute()
    {
        return 3;
    }
}