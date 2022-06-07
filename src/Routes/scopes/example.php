<?php

use SultanovPackage\MicroCommands\Controllers\ExampleController as ScopeController;
use Illuminate\Support\Facades\Route;

$scope = basename(__FILE__, '.php');;

Route::group(['prefix' => $scope, 'as' => $scope . '.'], function () {
    #Create
    Route::post('/', [ScopeController::class, 'create'])->name('create');
    #Read
    Route::get('{id}', [ScopeController::class, 'read'])->where(['id' => '[0-9]+'])->name('read');
    #Update
    Route::put('/', [ScopeController::class, 'update'])->name('update');
    #Destroy
    Route::delete('/', [ScopeController::class, 'destroy'])->name('destroy');
    #List
    Route::get('/', [ScopeController::class, 'list'])->name('list');
});
