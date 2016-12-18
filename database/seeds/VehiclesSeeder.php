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
        factory(App\Date::class, 500)->make()->each(function($item, $key){
            $item->date= \App\Carbon::now()->setTime(0,0,0)->addDay($key);
            $item->semi_trailer_truck()->save(factory(App\SemiTrailerTruck::class)->make());
            $item->swap_body_truck()->save(factory(App\SwapBodyTruck::class)->make());
            $item->pup_trailer()->save(factory(App\PupTrailer::class)->make());
            $item->save();
        });

    }
}
