<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            Team::insert([
                ['name' => 'Chelsea', 'logo'                => 'chelsea.png'],
                ['name' => 'Manchester United', 'logo'      => 'Manchester_United.png'],
                ['name' => 'Arsenal', 'logo'                => 'arsenal.png'],
                ['name' => 'Liverpool', 'logo'              => 'liverpool.png'],
                ['name' => 'Manchester City', 'logo'        => 'ManchesterCity.png'],
                ['name' => 'West Ham United', 'logo'        => 'West_Ham_United.png'],
            ]);
        });
    }
}
