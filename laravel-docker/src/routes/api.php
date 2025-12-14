<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotizensController;

Route::get('/notiz',[NotizensController::class,'getAll']);			// note, this is here just for debugging and should be removed for production.
Route::get('/notiz/pg/{page}',[NotizensController::class,'getPage']);
Route::get('/notiz/count',[NotizensController::class,'getCount']);
Route::get('/notiz/{id}',[NotizensController::class,'getNotiz']);
Route::delete('/notiz/{id}',[NotizensController::class,'deleteNotiz']);
Route::post('/notiz/post',[NotizensController::class,'createNotiz']);
Route::put('/notiz/{id}',[NotizensController::class,'updateNotiz']);
