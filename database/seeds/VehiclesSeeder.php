<?php


use Illuminate\Database\Seeder;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        factory(App\SemiTrailerTruck::class, 500)->make()->each(function($item, $key){
            $item->date_id = $key + 1;
            $item->save();
        });
        factory(App\SwapBodyTruck::class, 500)->make()->each(function($item, $key){
            $item->date_id = $key + 1;
            $item->save();
        });
        factory(App\PupTrailer::class, 500)->make()->each(function($item, $key){
            $item->date_id = $key+1;
            $item->save();
        });
        factory(App\Date::class, 500)->make()->each(function($item,$key){
            $item->date= \App\Carbon::now()->setTime(0,0,0)->addDay($key);
            $item->save();
        });
    }
}
