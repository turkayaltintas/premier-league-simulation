<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TeamTableSeeder::class);
        $this->call(SeasonTableSeeder::class);
        $this->call(TeamStrengthTableSeeder::class);
        $this->call(WeekTableSeeder::class);
    }
}
