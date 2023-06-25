<?php

use App\Http\Controllers\UnitTestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(UnitTestController::class)->prefix('collection')->group(function () {
    Route::get('/create-collection', 'createCollection');
});

Route::get('/collapse', function () {
    $collection = collect([
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9]
    ]);
    $result = $collection->collapse();

    dd($result);
});
