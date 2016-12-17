<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Date extends Model
{
    public function semi_trailer_truck(){
        return $this->hasOne('App\SemiTrailerTruck');
    }

    public function pup_trailer(){
        return $this->hasOne('App\PupTrailer');
    }

    public function swap_body_truck(){
        return $this->hasOne('App\SwapBodyTruck');
    }

    /**
     * A scope to filter into a spesific month and spesific year
     * @param $query Builder
     * @param $date
     * @return mixed
     */
    public function scopeMonthFilter($query, $date){
        $date = Carbon::parse($date);
        return $query->whereMonth('date',$date->month)->whereYear('date',$date->year);
    }

    /**
     * A scope to make sure that no past date is in the query
     * @param $query Builder
     * @return mixed
     */
    public function scopeExcludePastDate($query){
        return $query->where('date','>=',Carbon::now()->toDateString());
    }

    public function scopefilterWeekDaysIfValueGiven($query,$weekdays){
        return $query->whereIn("WEEKDAY(date)",$weekdays);
    }
}
