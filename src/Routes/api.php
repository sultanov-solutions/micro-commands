<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([], function () {
    collect(scandir(__DIR__ . '/scopes'))
        ->filter(fn($d) => $d !== '.' && $d !== '..')
        ->each(fn($d) => require_once __DIR__ . '/scopes/' . $d);
});
