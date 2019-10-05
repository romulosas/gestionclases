<?php

Route::group([ 'middleware' => ['auth','verified']], function () {

    Route::get('/home', function () {
        return redirect('home/dashboard');
    });

    Route::group(['namespace' => 'Home', 'prefix' => 'home'], function () {

        Route::get('dashboard', 'DashboardController@index')->name('home/dashboard');

    });

});

