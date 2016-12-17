<?php

namespace App\Http\Controllers;

use App\Carbon;
use App\Date;
use App\PupTrailer;
use App\SemiTrailerTruck;
use App\SwapBodyTruck;
use App\VehiclesModel;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehiclesController extends Controller
{

    /**
     * Display all vehicles that grouped by the month
     * By default it will return the current month data
     * and exclude past dates
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->month){
            $month=  Carbon::parse($request->month);
        } else {
            $month = Carbon::now();
        }
        $is_this_month = $month->isSameMonth(Carbon::now());
        $apiRoute = '/api/vehicles?month=';
        $vehicles = $this->indexVehiclesByMonth($month);
        $current_month = $month->toDateString();
        $next_month = $month->addMonthNoOverflow()->toDateString();
        $previous_month = $month->subMonthNoOverflow()->toDateString();
        $current_month_url = $apiRoute.$current_month;
        $next_month_url =  $apiRoute.$next_month;
        $previous_month_url =  $apiRoute.$previous_month;
        $returndata = compact('vehicles','current_month'
            ,'next_month','previous_month','current_month_url', 'next_month_url',
            'previous_month_url', 'is_this_month');
        return response($returndata);
    }

    public function update(Request $request)
    {
        $modelToBeUpdated = null;
        $additionalRules = null;
        if($request->name == 'semi_trailer_truck'){
            $modelToBeUpdated = SemiTrailerTruck::class;
        }
        if($request->name == 'swap_body_truck'){
            $modelToBeUpdated = SwapBodyTruck::class;
            $additionalRules = ['pupTrailerExceeding'=>$request->pk];
        }
        if($request->name == 'pup_trailer'){
            $modelToBeUpdated = PupTrailer::class;
            $additionalRules = ['dontExceedSwapBodyTruck'=>$request->pk];
        }
        $validator = $this->updateValidation($request, $modelToBeUpdated,$additionalRules);
        if($validator->fails()){
            $errors = null;
            foreach ($validator->errors()->all() as $key =>$error){
                if($key == 0){
                    $errors = $error.' ';
                }else{
                    $errors .= $error;
                }
            }
            return response($errors,422);
        }
        $this->updateSingleModel($modelToBeUpdated,$request->pk,$request->field,$request->value);
        $vehicles = $this->indexVehiclesByMonth(Carbon::parse($request->month));
        $response = array_merge($request->all(),['vehicles'=>$vehicles]);
        return response($response);
    }

    public function bulkUpdate(Request $request)
    {
        $filterWeekDay = $this->bulkUpdateGetWeekDays($request->refine_days,$request->custom_refine_days);
        $dates = Date::with($request->select_vehicle)
            ->where('date','>=',$this->setTimeToMidnight($request->start_date))
            ->where('date','<=', $this->setTimeToMidnight($request->end_date)->addDay())
            ->whereIn(\DB::raw('WEEKDAY(date)'),$filterWeekDay)
            ->get()
        ;
        $dates->each(function($date) use($request){
            collect($request->select_vehicle)->each(function($vehicle)use($date,$request){
                $date->$vehicle->price = $request->price;
                $date->$vehicle->vehicles_available = $request->vehicles_availability;
                $date->$vehicle->save();
            });
        });
        $vehicles = $this->indexVehiclesByMonth(Carbon::parse($request->current_month));
        return response(['vehicles'=>$vehicles]);
    }

    private function bulkUpdateGetWeekDays($refine_days_value, $custom_refine_days = null)
    {
        if($refine_days_value == 'alldays'){
            return [0,1,2,3,4,5,6];
        }
        if($refine_days_value == 'weekdays'){
            return [0,1,2,3,4];
        }
        if($refine_days_value == 'weekend'){
            return [5,6];
        }
        if($refine_days_value == 'custom'){
            return  $custom_refine_days;
        }
    }

    /**
     * @param $time
     * @return Carbon
     */
    private function setTimeToMidnight($time)
    {
        return Carbon::parse($time)->setTime(0,0,0);
    }

    private function updateValidation(Request $request, $model, $additionalRules = null)
    {
        if($request->field == 'vehicles_available'){
            return $this->vehicles_available_validation($request,new $model,$additionalRules);
        }
        if($request->field == 'price'){
            return $this->priceValidation($request);
        }
    }

    private function vehicles_available_validation(Request $request,$model, $additionalrules = null)
    {
        $currentrules = $model->vehiclesAvailableValidationRules();
        if(is_array($additionalrules)){
            $currentrules = $this->addAdditionalRules($currentrules, $additionalrules);
        }
        return \Validator::make($request->all(),['value'=>$currentrules]);
    }

    private function priceValidation(Request $request)
    {
        return  \Validator::make($request->all(),['value'=>'required|integer']);
    }

    private function addAdditionalRules($currentrules, Array $additionalrules)
    {
        $validationrules= $currentrules;
        foreach ($additionalrules as $key=> $value){
            $validationrules = $currentrules.'|'.$key.':'.$value;
        }
        return $validationrules;
    }

    private function updateSingleModel($model,$pk, $field, $value)
    {
        $modelToBeUpdated = $model::find($pk);
        $modelToBeUpdated->$field = $value;
        $modelToBeUpdated->save();
    }

    private function indexVehiclesByMonth(Carbon $month)
    {
        return Date::with('semi_trailer_truck','pup_trailer','swap_body_truck')
            ->monthFilter($month->toDateString())
            ->excludePastDate()
            ->get();
    }

}
