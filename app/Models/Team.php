<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psy\Command\WhereamiCommand;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'power'
    ];

    protected $appends = [
        'pts',
        'p',
        'd',
        'l',
        'w',
        'gd'
    ];


    public function gell(){return 'ok';}

    private function leagueMatches($league_id)
    {

        $matches = TMatch::where('league_id', $league_id)
            ->where('result', '!=', '')
            ->where(function ($q) {
                $q->where('t1_id', $this->id)
                    ->orWhere('t2_id', $this->id);
            });
        return $matches;
    }
    public function getPtsAttribute()
    {
        $league_id = CurrentLeague::first()->id;
        $matches = $this->leagueMatches($league_id)->get();
        $pts = 0;
        foreach ($matches as $match) {
            $scores = explode('-', $match->result);
            if ($match->t1_id == $this->id) {
                if (intval($scores[0]) > intval($scores[1])) $pts += 3;
                else if (intval($scores[0]) == intval($scores[1])) $pts += 1;
            } else {
                if (intval($scores[1]) > intval($scores[0])) $pts += 3;
                else if (intval($scores[1]) == intval($scores[0])) $pts += 1;
            }
        }
        return $pts;
    }

    public function getPAttribute()
    {
        $league_id = CurrentLeague::first()->id;
        return $this->leagueMatches($league_id)->count();
    }
    public function getDAttribute()
    {
        $league_id = CurrentLeague::first()->id;
        $matches = $this->leagueMatches($league_id)->get();
        $counter = 0;
        foreach ($matches as $match) {
            $scores = explode('-', $match->result);
            if (intval($scores[0]) === intval($scores[1])) $counter++;
        }
        return $counter;
    }
    public function getLAttribute()
    {
        $league_id = CurrentLeague::first()->id;

        $matches = $this->leagueMatches($league_id)->get();
        $counter = 0;
        foreach ($matches as $match) {
            $scores = explode('-', $match->result);
            if ($match->t1_id === $this->id) {

                if (intval($scores[0]) < intval($scores[1])) $counter++;
            } else 
                if (intval($scores[1]) < intval($scores[0])) $counter++;
        }
        return $counter;
    }
    public function getWAttribute()
    {
        $league_id = CurrentLeague::first()->id;
        $matches = $this->leagueMatches($league_id)->get();
        $counter = 0;
        foreach ($matches as $match) {
            $scores = explode('-', $match->result);
            if ($match->t1_id == $this->id) {
                if (intval($scores[0]) > intval($scores[1])) $counter++;
            } else if (intval($scores[1]) > intval($scores[0])) $counter++;
        }
        return $counter;
    }
    public function getGdAttribute()
    {
        $league_id = CurrentLeague::first()->id;
        $matches = $this->leagueMatches($league_id)->get();
        $counter = 0;
        foreach ($matches as $match) {
            $scores = explode('-', $match->result);
            if ($match->t1_id == $this->id)
                $counter += intval($scores[0]) - intval($scores[1]);
            else
                $counter += intval($scores[1]) - intval($scores[0]);
        }
        return $counter;
    }
}
