<?php

use Illuminate\Support\Facades\Route;

$app_url = config("app.url");
if (!empty($app_url))
{
    URL::forceRootUrl($app_url);
    $schema = explode(':', $app_url)[0];
    URL::forceScheme($schema);

}

Route::middleware("guest:admin")->group(function() {
    Route::get('/', function () {return redirect()->route('admin.login');});
    Route::get('login', [\App\Http\Controllers\Admin\AuthController::class, 'index'])->name('login');
    Route::post('login_process', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login_process');
});
Route::middleware("auth:admin")->group(function() {
    Route::get('logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    Route::resource('settings', \App\Http\Controllers\Admin\SettingsContoller::class);
    Route::get('/users', 'App\Http\Controllers\Admin\UserController@index')->name('users.index');
});
