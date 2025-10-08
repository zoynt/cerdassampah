<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\OrderController;

Route::get('/leaderboard', [LeaderboardController::class, 'index']); // tanpa auth dulu untuk uji
