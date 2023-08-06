<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class League extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'week',
        'teams_id'
    ];


    public static function createNewLeague()
    {

        CurrentLeague::truncate();
        League::truncate();
        TMatch::truncate();
        $l = new League();
        $l->createLeagueToBeMatches();
        
    }

    public function currentWeekMatches()
    {
        $league = League::findOrFail(CurrentLeague::first()->id);

        return TMatch::where('league_id', $league->id)
            ->where('weak', $league->week)->get();
    }

    public function getPredictionsOfChampionship()
    {
        $currentLeague = League::currentLeague();
        if($currentLeague->week <4) return false;
        $leagueList = $this->getLeagueList();
        $teams = [$leagueList[0]];
        for ($i = 1; $i < 4; $i++) {
            if ($leagueList[0]->pts <= ($leagueList[$i]->pts + ((6 - $currentLeague->week)*3)))
                $teams[] = $leagueList[$i];
        }
        $totalRate = 0;
        $temp = [];
        foreach ($teams as $team) {
            $temp[] =  [
                'team' => $team,
                'temp_rate' => $team->power * $team->pts,
            ];
            $totalRate += $team->power * $team->pts;
        }
        $result = [];
        foreach ($temp as $row) {
            $result[] = [
                'team' => $row['team'],
                'rate' => round($row['temp_rate'] / $totalRate * 100),
            ];
        }
        return $result;
    }


    public static function currentLeague()
    {
        $currentLeague = CurrentLeague::first();
        if (!$currentLeague) {
            $l = new League();
            $l->createLeagueToBeMatches();
            $currentLeague = CurrentLeague::first();
        }
        return League::findOrFail($currentLeague->current_league_id);
    }

    public function getLeagueList()
    {
        $leagueTeams = Team::whereIn('id', explode('-', $this->teams_id))
            ->get();
        return $this->sortList($leagueTeams);
    }

    private function sortList($lt)
    {

        $sorted = json_decode(json_encode($lt));
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < 4; $j++) {
                if ($sorted[$i]->pts > $sorted[$j]->pts && $j > $i) {
                    $temp = $sorted[$i];
                    $sorted[$i] = $sorted[$j];
                    $sorted[$j] = $temp;
                } else if ($sorted[$i]->pts == $sorted[$j]->pts && $j > $i && $sorted[$i]->gd > $sorted[$j]->gd) {
                    $temp = $sorted[$i];
                    $sorted[$i] = $sorted[$j];
                    $sorted[$j] = $temp;
                }
            }
        }

        return array_reverse($sorted);
    }


    public function createLeagueToBeMatches()
    {
        $teams = Team::inRandomOrder()->limit(4)->get();
        $teams_id = [];
        foreach ($teams as $team) {
            array_push($teams_id, $team->id);
        }

        $league = League::create(['teams_id' => join('-', $teams_id)]);
        CurrentLeague::create([
            'current_league_id' => $league->id
        ]);
        $matches = [];
        $pairedTeamsIndexes = $this->generatePairTeamsIndexes();
        foreach ($pairedTeamsIndexes as $index) {
            $matches[] = [
                [$teams[$index[0][0]], $teams[$index[0][1]]],
                [$teams[$index[1][0]], $teams[$index[1][1]]],
            ];
        }
        $temp = $matches;
        foreach ($temp as $wm) {
            $matches[] = array_reverse($wm);
        }


        foreach ($matches as $key => $val) {
            foreach ($val  as $t) {
                TMatch::create([
                    't1_id' => $t[0]->id,
                    't2_id' => $t[1]->id,
                    'weak' => $key + 1,
                    'league_id' => $league->id
                ]);
            }
        }
        return $matches;
    }

    private function generatePairTeamsIndexes()
    {
        $matches = [];
        foreach (range(0, 2) as $i) {
            foreach (range(0, 2) as $j) {
                if ($i >= $j) continue;
                $indexes = range(0, 3);
                $firstMatch = [
                    $indexes[$i],
                    $indexes[$j]
                ];
                unset($indexes[$i]);
                unset($indexes[$j]);

                $secondMatch = [
                    array_pop($indexes),
                    array_pop($indexes),
                ];
                $matches[] = [$firstMatch, $secondMatch];
            }
        }

        return $matches;
    }
}
