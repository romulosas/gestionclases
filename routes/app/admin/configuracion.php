<?php


Route::group([ 'middleware' => 'auth','verified'], function () {

    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

        Route::group(['namespace' => 'Configuracion', 'prefix' => 'configuracion'], function () {

            Route::group(['namespace' => 'Curso'], function () {

                Route::resource('curso', 'CursoController');
                Route::resource('estadocurso', 'EstadoCursoController');
            });

        });

    });

});