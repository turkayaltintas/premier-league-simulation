<?php

namespace Database\Seeders;

use App\Models\Week;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WeekTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {
            Week::insert([
                ['name' => '1 st Week', 'season_id' => 1],
                ['name' => '2 nd Week', 'season_id' => 1],
                ['name' => '3 rd Week', 'season_id' => 1],
                ['name' => '4 th Week', 'season_id' => 1],
                ['name' => '5 th week', 'season_id' => 1],
                ['name' => '6 th week', 'season_id' => 1],
            ]);
        });
    }
}
