<?php
namespace App\Helpers;

class MatchHelper{


        const MAX_GOAL_LENGTH = 5;
        
        public static function playMatch ($match){

        $powerDiff = $match->hostTeam->power - $match->awayTeam->power;
        $powerDiff = $powerDiff > 0 ? $powerDiff : -$powerDiff;
        $match->hostTeam->power = $match->hostTeam->power + $powerDiff;


        $goalCount = rand(0,self::MAX_GOAL_LENGTH);
        $result = [
            'hostTeam' => 0,
            'awayTeam'=> 0
        ]; 
        foreach(range(0,$goalCount) as $n){
            $whoScoded = rand(0,$match->hostTeam->power + $match->awayTeam->power);
            if($whoScoded <= $match->hostTeam->power) $result['hostTeam']++; 
            else $result['awayTeam']++;
        }
        return $result;
    }




}