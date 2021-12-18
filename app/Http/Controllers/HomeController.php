<?php

namespace App\Http\Controllers;

use App\Repositories\LeagueRepository;
use App\Repositories\PlayRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public $league;
    public $teams;
    public $weeks;
    public $leagueRepository;
    public $playRepository;
    public $fixture;
    public $result = array();
    public $predictions = array();

    /**
     * HomeController constructor.
     * @param LeagueRepository $leagueRepository
     * @param PlayRepository $playRepository
     */
    public function __construct(LeagueRepository $leagueRepository, PlayRepository $playRepository)
    {
        $this->leagueRepository = $leagueRepository;
        $this->playRepository = $playRepository;
        $this->leagueRepository->createLeague();
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLeague()
    {
        $this->weeks = $this->playRepository->getWeeks();
        $this->league = $this->leagueRepository->getAll();
        $this->playRepository->createFixture();
        $this->fixture = $this->playRepository->getFixture();
        $collection = collect($this->fixture);
        $grouped = $collection->groupBy('week_id');
        $strength = $this->playRepository->getAllStrenght();


        return view(
            'pages/home',
            ['league' => $this->league,
                'matches' => $grouped->toArray(),
                'fixture' => $grouped->toArray(),
                'weeks' => $this->weeks,
                'strength' => $strength,
                'types' => array('weak', 'average', 'strong')
            ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshFixture()
    {
        $this->weeks = $this->playRepository->getWeeks();
        $this->fixture = $this->playRepository->getFixture();
        $collection = collect($this->fixture);
        $grouped = $collection->groupBy('week_id');
        return response()->json(array('weeks' => $this->weeks, 'items' => $grouped->toArray()));
    }

    /**
     *
     */
    public function play()
    {

        $matches = $this->playRepository->getAllMatches();
        $this->playGame($matches);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshLeauge()
    {
        $this->league = $this->leagueRepository->getAll();
        return response()->json($this->league);
    }


    /**
     * @param $week
     * @return \Illuminate\Http\JsonResponse
     */
    public function playWeekly($week)
    {
        $matches = $this->playRepository->getMatchesFromWeek($week);
        $this->playGame($matches);
        $result = $this->playRepository->getFixtureByWeekId($week);

        return response()->json(array('matches' => $result));
    }


    public function reset()
    {
        $this->playRepository->truncateMatches();
        $this->leagueRepository->truncateLeauge();
        $this->playRepository->createFixture();
    }

    /**
     * @param $matches
     */
    private function playGame($matches)
    {
        foreach ($matches as $match) {
            $homeScore = $this->playRepository->createStrenght($match->home, 1);
            $awayScore = $this->playRepository->createStrenght($match->away, 0);
            $home = $this->leagueRepository->getLeaugeByTeamId($match->home);
            $away = $this->leagueRepository->getLeaugeByTeamId($match->away);
            $this->playRepository->calculateScore($homeScore, $awayScore, $home, $away);
            $match->home_goal = $homeScore;
            $match->away_goal = $awayScore;
            $match->played = 1;
            $match->save();
        }

    }

    /**
     * @param $week
     * @return \Illuminate\Http\JsonResponse
     */
    public function nextMatches($week)
    {
        $matches = $this->playRepository->getFixtureByWeekId($week);
        return response()->json(array('matches' => $matches));

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function predictions()
    {
        $finished = $this->leagueRepository->getAll();
        $this->collectionPredictions($finished);
        $matches = $this->playRepository->getAllMatches();
        $this->combinePredictions($matches);
        $collection = collect($this->predictions);
        $multiplied = $collection->map(function ($item) {
            return round((($item['points'] / $this->sumPoints()) * 100), 2);
        });

        $combine = $multiplied->all();

        //reset keys after combine
        $values = $collection->values();

        $chart = array();
        foreach ($values->all() as $key => $val) {
            array_push($chart, [$val['name'], $combine[$val['team_id']]]);
        }

        return response()->json(array('items' => $chart));
    }

    /**
     * @param $matches
     */
    private function combinePredictions($matches)
    {
        foreach ($matches as $match) {
            $homeScore = $this->playRepository->createStrenght($match->home, 1);
            $awayScore = $this->playRepository->createStrenght($match->away, 0);

            $points = $this->calculateScoreForChart($homeScore, $awayScore);
            if (isset($points['away'])) {
                foreach ($points['away'] as $key => $value) {
                    $this->predictions[$match->away][$key] += $points['away'][$key];
                }
            }
            if (isset($points['home'])) {
                foreach ($points['home'] as $key => $value) {
                    $this->predictions[$match->home][$key] += $points['home'][$key];
                }
            }
        }
    }

    /**
     * @param $data
     */
    private function collectionPredictions($data)
    {
        $collection = collect($data);
        $collection->each(function ($item) {
            $this->predictions[$item->team_id]['points'] = $item->points;
            $this->predictions[$item->team_id]['name'] = $item->name;
            $this->predictions[$item->team_id]['team_id'] = $item->team_id;
        });
    }
    /**
     * @param $homeScore
     * @param $awayScore
     * @return array
     */
    public function calculateScoreForChart($homeScore, $awayScore)
    {
        $points = array();
        if ($homeScore > $awayScore) {
            $points['home']['points'] = 3;
        } elseif ($awayScore > $homeScore) {
            $points['away']['points'] = 3;
        } else {
            $points['home']['points'] = 1;
            $points['away']['points'] = 1;
        }
        return $points;
    }

    /**
     * @return float|int
     */
    private function sumPoints()
    {
        return array_sum(array_map(function ($item) {
            return $item['points'];
        }, $this->predictions));
    }

    public function updateMatch($id,$column,$value)
    {
        $this->playRepository->updateMatch($id,$column,$value);
        $this->leagueRepository->truncateLeauge();
        $this->leagueRepository->createLeague();
        $matches = $this->playRepository->getAllMatches(1);

        foreach ($matches as $match){
            $home = $this->leagueRepository->getLeaugeByTeamId($match->home);
            $away = $this->leagueRepository->getLeaugeByTeamId($match->away);

            $this->playRepository->calculateScore($match->home_goal, $match->away_goal, $home, $away);
        }
    }
}
