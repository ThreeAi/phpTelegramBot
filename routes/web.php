<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$app_url = config("app.url");
if (!empty($app_url))
{
    URL::forceRootUrl($app_url);
    $schema = explode(':', $app_url)[0];
    URL::forceScheme($schema);

}

Route::get('/', function () {
    return view('welcome');
});
Route::post('/');

Route::post('/webhook', App\Http\Controllers\WebhookController::class);

Route::get('/settings', 'App\Http\Controllers\SettingsController@index')->name('settings.index');
Route::get('/settings/create', 'App\Http\Controllers\SettingsController@create')->name('settings.create');
Route::post('/settings', 'App\Http\Controllers\SettingsController@store')->name('settings.store');
Route::get('/settings/{setting}', 'App\Http\Controllers\SettingsController@show')->name('settings.show');
Route::get('/settings/{setting}/edit', 'App\Http\Controllers\SettingsController@edit')->name('settings.edit');
Route::patch('/settings/{setting}', 'App\Http\Controllers\SettingsController@update')->name('settings.update');
Route::delete('/settings/{setting}', 'App\Http\Controllers\SettingsController@destroy')->name('settings.delete');

Route::get('/users', 'App\Http\Controllers\UserController@index')->name('users.index');
Route::get('/main', 'App\Http\Controllers\MainController@index')->name('main.index');
