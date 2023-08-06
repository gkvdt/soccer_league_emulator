<?php

use App\Http\Controllers\LeagueController;
use Illuminate\Support\Facades\Route;




Route::get('/', [LeagueController::class, 'index'])->name('home');
Route::get('/playMatchesOfWeek', [LeagueController::class, 'playMatchesOfWeek'])->name('play');
Route::get('/playMatchesAllOfLeague', [LeagueController::class, 'playMatchesAllOfLeague'])->name('playAll');
Route::get('/resetLeague', [LeagueController::class, 'resetLeague'])->name('resetLeague');

