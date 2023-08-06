<?php

namespace App\Http\Controllers;

use App\Helpers\MatchHelper;
use App\Models\League;
use App\Models\Team;
use App\Models\TMatch;

class LeagueController extends Controller
{
    public function index()
    {
        $currentLeague = League::currentLeague();
        $this->currentLeague = $currentLeague;
        $this->leagueList = $currentLeague->getLeagueList();
        $this->league_id = $currentLeague->id;
        $this->currentWeekMatches = $currentLeague->currentWeekMatches();
        $this->predictionsOfChampionships = $currentLeague->getPredictionsOfChampionship();
        return view('home', $this->data);
        return json_encode($this->data);
    }
    public function playMatchesOfWeek()
    {
        $currentLeague = League::currentLeague();
        if ($currentLeague->week >= 6) {
            $currentLeague->status = false;
            $currentLeague->save();
            return;
        }
        $currentLeague->week =  $currentLeague->week + 1;
        $currentLeague->save();
        $matches = TMatch::where('weak', $currentLeague->week)->get();
        foreach ($matches as $match) {
            $res = MatchHelper::playMatch($match);
            $match->result = join('-', $res);
            $match->save();
        }
        if ($currentLeague->week == 6) {
            $currentLeague->status = false;
            $currentLeague->save();
        }
        return redirect()->to(route('home'));
    }
    public function resetLeague(){
        League::createNewLeague();
        return redirect()->to(route('home'));
    }


    public function playMatchesAllOfLeague()
    {
        $matches = TMatch::where('result', null)->get();
        $currentLeague = League::currentLeague();
        $currentLeague->week =  6;
        $currentLeague->save();
        foreach ($matches as $match) {
            $res = MatchHelper::playMatch($match);
            $match->result = join('-', $res);
            $match->save();
        }
        $currentLeague->status = false;
        $currentLeague->save();
        return redirect()->to(route('home'));
    }
}
