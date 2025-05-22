<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/request-loop-no',[\App\Http\Controllers\LoopNumberRequestController::class,'index']);
Route::post('/request',[\App\Http\Controllers\LoopNumberRequestController::class,'requestLoop']);
Route::get('/request-loop-no/success',[\App\Http\Controllers\LoopNumberRequestController::class,'success']);
Route::get('/update-instrument-index/{sessionId}',[\App\Http\Controllers\LoopNumberRequestController::class,'edit']);
Route::post('/instrument-index/update',[\App\Http\Controllers\LoopNumberRequestController::class,'update']);
