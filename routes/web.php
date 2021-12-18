<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'getLeague']);
Route::get('/play-all', [HomeController::class, 'play']);
Route::get('/play-week/{week}', [HomeController::class, 'playWeek']);
Route::get('/edit-strenght', [HomeController::class, 'editStrenght']);

Route::prefix('api')->group(function() {
    Route::get('fixture', [HomeController::class,'refreshFixture']);
    Route::get('leauge',  [HomeController::class,'refreshLeauge']);
    Route::get('reset',  [HomeController::class,'reset']);

    Route::get('next-matches/{week}',  [HomeController::class,'nextMatches']);
    Route::get('/play-weekly/{week}',  [HomeController::class,'playWeekly']);
    Route::get('/predictions',  [HomeController::class,'predictions']);
    Route::get('/update-match/{id}/{column}/{value}',  [HomeController::class,'updateMatch']);
});
