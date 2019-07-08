<?php

Route::group(['middleware' => ['web', 'permission:' . \Gcms::MAIN_ADMIN_PERMISSION], 'prefix' => getAdminPrefix('setting')], function () {
    Route::group(['middleware' => ['permission:modules_settings_admin_list']], function () {
        Route::get('/', 'GeekCms\Setting\Http\Controllers\AdminController@index')
            ->name('admin.setting')
        ;

        Route::get('/variables', 'GeekCms\Setting\Http\Controllers\AdminController@variables')
            ->name('admin.setting.variables')
        ;
    });

    Route::group(['middleware' => ['permission:modules_settings_admin_edit']], function () {
        Route::post('/save', 'GeekCms\Setting\Http\Controllers\AdminController@save')
            ->name('admin.setting.save')
        ;

        Route::post('/save-variables', 'GeekCms\Setting\Http\Controllers\AdminController@save_variables')
            ->name('admin.setting.variables.save')
        ;
    });

    Route::get('/service/clear-cache', 'GeekCms\Setting\Http\Controllers\ServiceController@clearCache')
        ->name('admin.setting.service.cache')
    ;

    Route::get('/service/clear-view', 'GeekCms\Setting\Http\Controllers\ServiceController@clearView')
        ->name('admin.setting.service.view')
    ;

    Route::get('/service/email-send', 'GeekCms\Setting\Http\Controllers\ServiceController@emailCheck')
        ->name('admin.setting.service.email')
    ;
});
