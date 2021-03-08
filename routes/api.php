<?php

use Illuminate\Support\Facades\Route;

Route::post('login', 'UserController@login');

Route::group(['middleware' => ['cors']], function()
{
   Route::get('users/details', 'UserController@details');
   
   Route::resource('users','UserController', ['except' => ['create','edit']]);

   Route::post('jobs/new', 'JobController@newJob')->name('new.job');

   Route::get('jobs/get', 'JobController@getJob');

   Route::get('jobs/{id}', 'JobController@checkJob')->where('id', '[0-9]+');
   
   
   
   
   
});