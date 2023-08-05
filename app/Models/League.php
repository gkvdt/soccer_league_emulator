<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

        CurrentLeague::delete();
        $currentLeagueId = self::createLeagueToBeMatches();
        CurrentLeague::create([
            'current_league_id'=> $currentLeagueId
        ]);
    }


    public static function currentLeague()
    {
        return League::findOrFail(CurrentLeague::first()->current_league_id);
    }


    private function createLeagueToBeMatches()
    {
        $teams = Team::inRandomOrder()->limit(4)->get();
        $teams_id = [];
        foreach ($teams as $team) {
            array_push($teams_id, $team->id);
        }

        $league = League::create(['teams_id' => join('-', $teams_id)]);
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
