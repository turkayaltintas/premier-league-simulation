<?php

namespace App\Repositories;

use App\Models\Team;
use App\Models\Match;
use App\Models\TeamStrength;
use App\Models\Week;

class PlayRepository
{
    protected $team;
    protected $match;
    protected $week;
    protected $teamStrength;
    public $result;

    public function __construct(Team $team, Match $match, Week $week, TeamStrength $teamStrength)
    {
        $this->team = $team;
        $this->match = $match;
        $this->week = $week;
        $this->teamStrength = $teamStrength;
    }

    public function getTeamId()
    {
        return $this->team->pluck('id');
    }

    public function getWeeksId()
    {
        return $this->week->pluck('id');
    }

    public function getWeeks()
    {
        return $this->week->get();
    }

    public function createFixture()
    {
        foreach ($this->getWeeksId() as $week) {
            foreach ($this->iterateTeams($this->getTeamId()) as $value) {
                if (0 == $this->checkMatch($week, $value)) {
                    $this->match->create(['home' => $value[0], 'away' => $value[1], 'week_id' => $week]);
                }
            }
        }
    }

    private function iterateTeams($teams)
    {
        $collection = collect($teams);
        $matrix = $collection->crossJoin($teams);
        $data = $matrix->reject(function($items){

            if($items[0] == $items[1]){
                return $items;
            }
        })->shuffle();
        return $data->all();
    }

    public function checkMatch($week_id, $teams)
    {
        return $this->match->where('week_id', '=', $week_id)
            ->whereRaw('(home IN(' . implode(',', $teams) . ') OR away IN(' . implode(',', $teams) . '))')
            ->count();
    }

    public function getFixture()
    {

        return $this->match->select(
            'matches.id',
            'matches.played',
            'matches.week_id',
            'matches.home_goal',
            'matches.away_goal',
            'week_id',
            'home.name as home_team',
            'home.logo as home_logo',
            'away.logo as away_logo',
            'away.name as away_team')
            ->join('weeks', 'weeks.id', '=','matches.week_id')
            ->join('teams as home', 'home.id', '=','matches.home')
            ->join('teams as away', 'away.id', '=','matches.away')
            ->orderBy('week_id','ASC')
            ->get();
    }

    public function getFixtureByWeekId($week_id)
    {

        return $this->match->select(
            'matches.id',
            'matches.played',
            'matches.week_id',
            'matches.home_goal',
            'matches.away_goal',
            'week_id',
            'weeks.name',
            'home.logo as home_logo',
            'away.logo as away_logo',
            'home.name as home_team',
            'away.name as away_team')
            ->join('weeks', 'weeks.id', '=','matches.week_id')
            ->join('teams as home', 'home.id', '=','matches.home')
            ->join('teams as away', 'away.id', '=','matches.away')
            ->where('matches.week_id','=',$week_id)
            ->orderBy('matches.id','ASC')
            ->get();
    }

    public function getTeamStrenght($team_id, $is_home)
    {
        return $this->teamStrength->where([['team_id','=',$team_id],['is_home','=',$is_home]])->get();
    }

    public function createStrenght($team_id, $is_home)
    {

        foreach ($this->getTeamStrenght($team_id,$is_home) as $value){
            switch ($value->strength){
                case 'strong':
                    $this->result = rand(4,5);
                    break;
                case 'average':
                    $this->result = rand(2,3);
                    break;
                case 'weak' :
                    $this->result = rand(0,2);
                    break;
            }

            return $this->result;
        }
    }

    public function getMatchesFromWeek($week){
        return $this->match->where([['week_id','=',$week],['played','=',0]])->get();
    }

    /**
     * @param int $played
     * @return mixed
     */
    public function getAllMatches($played = 0)
    {
        return $this->match->where('played','=',$played)->get();
    }

    /**
     * @param $homeScore
     * @param $awayScore
     * @param $home
     * @param $away
     */
    public function calculateScore($homeScore, $awayScore, $home, $away)
    {
        if($homeScore > $awayScore){
            $home->won += 1;
            $home->points += 3;
            $home->goal_drawn += ($homeScore - $awayScore);
            $away->lose += 1;
            $away->goal_drawn  += ($awayScore - $homeScore);
        }elseif($awayScore > $homeScore){
            $away->won += 1;
            $away->points += 3;
            $away->goal_drawn += ($awayScore - $homeScore);
            $home->lose += 1;
            $home->goal_drawn += ($homeScore - $awayScore);
        }else{
            $home->draw += 1;
            $away->draw += 1;
            $home->points += 1;
            $away->points += 1;
        }
        $home->played  += 1;
        $away->played += 1;
        $home->save();
        $away->save();
    }


    public function truncateMatches()
    {
        $this->match->truncate();
    }

    public function getAllStrenght(){
        return $this->teamStrength->select('team_strengths.id','teams.name','teams.logo','team_strengths.is_home','team_strengths.strength')->join('teams','teams.id','=','team_strengths.team_id')->orderBy('teams.id')->get();
    }


    /**
     * @param $id
     * @param $column
     * @param $value
     * @return mixed
     */
    public function updateMatch($id, $column, $value)
    {
        $match = $this->match->find($id);
        $match->$column = $value;
        $match->played=1;
        $match->save();
        return $match;
    }
}
