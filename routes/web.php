<?php

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

Route::get('twitch', 'SiteController@twitch')->name('twitch');
Route::get('twitch/callback', 'SiteController@callback')->name('twitch-callback');
Route::get('twitch/subscribers', 'SiteController@subscribers')->name('twitch-subscribers');
Route::get('/', 'SiteController@index')->name('index');
