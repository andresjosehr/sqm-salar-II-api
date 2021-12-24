<?php

use Illuminate\Http\Request;
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

Route::post("register", "App\Http\Controllers\PassportAuthController@register");
Route::post("login", "App\Http\Controllers\PassportAuthController@login");
Route::post('reset-password-request', "App\Http\Controllers\PassportAuthController@resetPasswordRequest")->name('password.reset');
Route::post('reset-password', "App\Http\Controllers\PassportAuthController@resetPassword");

Route::get("get-terminal-data/{id_movil}", "App\Http\Controllers\TerminalsController@index");
Route::get("get-terminals-list", "App\Http\Controllers\TerminalsController@getTerminalsList");
Route::get("get-terminal-chart-data/{id_movil}", "App\Http\Controllers\TerminalsController@getTerminalChartData");
