<?php

namespace Database\Seeders;

use App\Models\TeamStrength;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamStrengthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {
            TeamStrength::insert([
                ['team_id' => 1, 'is_home' => 1,'strength' => 'strong'],
                ['team_id' => 1, 'is_home' => 0,'strength' => 'average'],
                ['team_id' => 2, 'is_home' => 1,'strength' => 'average'],
                ['team_id' => 2, 'is_home' => 0,'strength' => 'average'],
                ['team_id' => 3, 'is_home' => 1,'strength' => 'weak'],
                ['team_id' => 3, 'is_home' => 0,'strength' => 'average'],
                ['team_id' => 4, 'is_home' => 1,'strength' => 'strong'],
                ['team_id' => 4, 'is_home' => 0,'strength' => 'strong'],
                ['team_id' => 5, 'is_home' => 1,'strength' => 'average'],
                ['team_id' => 5, 'is_home' => 0,'strength' => 'weak'],
                ['team_id' => 6, 'is_home' => 1,'strength' => 'weak'],
                ['team_id' => 6, 'is_home' => 0,'strength' => 'average'],
            ]);
        });
    }
}
