<?php


Route::group([ 'middleware' => 'auth'], function () {

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

        Route::group(['namespace' => 'Configuracion', 'prefix' => 'configuracion'], function () {



        });

    });

});