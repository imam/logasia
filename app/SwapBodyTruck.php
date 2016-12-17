<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SwapBodyTruck extends VehiclesModel
{
    public $totalInventory = 4;

    public $vehiclesAvailableValidationRules;

    public function vehiclesAvailableValidationRules()
    {
        return  $this->vehiclesAvailableValidationRules = 'required|integer|min:0|max:'.$this->totalInventory;
    }

    public function getTotalInventoryAttribute()
    {
        return 4;
    }
}
