<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/request-loop-no',[\App\Http\Controllers\LoopNumberRequestController::class,'index']);
Route::post('/request',[\App\Http\Controllers\LoopNumberRequestController::class,'requestLoop']);
Route::get('/request-loop-no/success',[\App\Http\Controllers\LoopNumberRequestController::class,'success']);
Route::get('/update-instrument-index/{sessionId}',[\App\Http\Controllers\LoopNumberRequestController::class,'edit']);
Route::get('/getDataDev',[\App\Http\Controllers\LoopNumberRequestController::class,'getDataDev']);
Route::get('/getDataSetting',[\App\Http\Controllers\LoopNumberRequestController::class,'getDataSetting']);
Route::get('/getServices',[\App\Http\Controllers\LoopNumberRequestController::class,'getServices']);
Route::post('/instrument-index/update',[\App\Http\Controllers\LoopNumberRequestController::class,'update']);
Route::post('/finalize-instrument-index',[\App\Http\Controllers\LoopNumberRequestController::class,'finalize']);

Route::get('/instrument-index/import',[\App\Http\Controllers\ImportController::class,'index'])->middleware("auth");
Route::post('/instrument-index/import',[\App\Http\Controllers\ImportController::class,'import'])->middleware("auth");
Route::get('/service/import',[\App\Http\Controllers\ImportController::class,'indexService'])->middleware("auth");
Route::post('/service/import',[\App\Http\Controllers\ImportController::class,'importService'])->middleware("auth");
