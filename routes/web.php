<?php

use App\Http\Controllers\LeagueController;
use Illuminate\Support\Facades\Route;



Route::get('/', [LeagueController::class, 'create']);
Route::get('/playMatchesOfWeek', [LeagueController::class, 'playMatchesOfWeek']);

