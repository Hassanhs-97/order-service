<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\DashboardController;
use Inertia\Inertia;

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'prefix' => config('admin.prefix'),
    'middleware' => ['auth'],
    'as' => 'admin.',
], function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('user', 'UserController');
    Route::resource('role', 'RoleController');
    Route::resource('permission', 'PermissionController');
    Route::resource('items', 'ItemController');
    Route::get('orders/downloadOrderPdf/{order}', [OrderController::class, 'downloadOrderPdf'])->name('orders.downloadOrderPdf');
    Route::resource('orders', 'OrderController');
    Route::resource('menu', 'MenuController')->except([
        'show',
    ]);
    Route::resource('menu.item', 'MenuItemController');
    Route::group([
        'prefix' => 'category',
        'as' => 'category.',
    ], function () {
        Route::resource('type', 'CategoryTypeController')->except([
            'show',
        ]);
        Route::resource('type.item', 'CategoryController');
    });
    Route::get('edit-account-info', 'UserController@accountInfo')->name('account.info');
    Route::post('edit-account-info', 'UserController@accountInfoStore')->name('account.info.store');
    Route::post('change-password', 'UserController@changePasswordStore')->name('account.password.store');
});
