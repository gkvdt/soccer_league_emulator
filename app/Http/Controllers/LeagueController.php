<?php

namespace App\Http\Controllers;

use App\Helpers\MatchHelper;
use App\Models\League;
use App\Models\Team;
use App\Models\TMatch;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Match_;

class LeagueController extends Controller
{

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
        if($currentLeague->week == 6){
            $currentLeague->status = false;
            $currentLeague->save();
        }
    }



    private function playMatchesAllOfLeague()
    {
        $currentLeague = League::currentLeague();
        $currentLeague->week =  $currentLeague->week + 1;
        $currentLeague->save();
        $matches = TMatch::where('result', null)->get();
        foreach ($matches as $match) {
            $res = MatchHelper::playMatch($match);
            $match->result = join('-', $res);
            $match->save();
        }
        $currentLeague->status = false;
        $currentLeague->save();
    }

  
}
