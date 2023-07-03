<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/




$router->get('/', function () use ($router) {
    echo "<center> Welcome </center>";
});


Route::group(['prefix' => 'api'], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('profile', 'AuthController@profile');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('forgot', 'AuthController@forgotpassword');

    Route::post('setevaluation[/{id}]', 'AuthController@setevaluation');
    Route::post('getevaluation[/{id}]', 'AuthController@getevaluation');

    Route::post('deleteRow', 'AuthController@deleteRow');

    Route::post('admin/getevaluation[/{id}]', 'AdminController@getevaluation');
    Route::get('dublicate[/{id}]', 'AdminController@dublicateCopy');

    Route::post('setjson', 'FormController@setJson');
    Route::get('getjson[/{id}]', 'FormController@getJson');
    Route::get('getsteps[/{id}]', 'FormController@getSteps');

    Route::get('checkformsubmited[/{id}]', 'FormController@checkAllFormSubmited');

    Route::post('checkoutform', 'CheckoutController@fetchRequest');
    Route::post('assignusers', 'CheckoutController@assignUsers');
    Route::post('freetrail', 'CheckoutController@freeTrail');
    Route::post('check_free', 'CheckoutController@checkFreetrail');
    Route::post('get_packages', 'CheckoutController@getPackages');
    Route::post('get_assigned', 'CheckoutController@getAssignedUsers');
    Route::post('delink_assigned', 'CheckoutController@deleteAssignedUser');
    Route::post('check_if_assigned', 'CheckoutController@getIfAssigned');
    Route::post('check_scorecards', 'CheckoutController@checkScorecard');
    
    Route::post('getusers', 'AdminController@getusers');


});


