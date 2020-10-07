<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/logout', function(){
   Auth::logout();
   return Redirect::to('login');
});

Route::get('', 'UsersController@login');
Route::post('/process_login', 'UsersController@process_login');

Route::get('login', [ 'as' => 'login', 'uses' => 'UsersController@login']);

Route::group(['middleware' => 'auth'], function() {
	Route::get('/dashboard', 'UsersController@dashboard');
	Route::get('/userslocations', 'UsersController@userslocations');

	Route::get('/users/{view}', 'UsersController@users');
	Route::post('/create_user', 'UsersController@create_user');
	Route::post('/edit_user', 'UsersController@edit_user');	
	Route::post('/importCsvUsers', 'UsersController@importCsvUsers');

	Route::get('/company/{view}', 'CompanyController@company');
	Route::post('/create_company', 'CompanyController@create_company');
	Route::post('/edit_company', 'CompanyController@edit_company');
		
	// REPORTS 
	Route::get('/rpt_active_cases', 'ReportsController@rpt_active_cases');
	Route::get('/rpt_1stdegree_endangered/{userid}', 'ReportsController@rpt_1stdegree_endangered');
	Route::get('/rpt_2nddegree_endangered/{userid}', 'ReportsController@rpt_2nddegree_endangered');
	Route::get('/rpt_defaulters', 'ReportsController@rpt_defaulters');
	Route::get('/rpt_breaches', 'ReportsController@rpt_breaches');	
	Route::get('/rpt_usershealth', 'ReportsController@rpt_usershealth');
	Route::get('/rpt_usersbtdistances', 'ReportsController@rpt_usersbtdistances');

	// EXCEL
	Route::get('/exl_usersbtdistances', 'ReportsController@exl_usersbtdistances');
	Route::get('/exl_defaulters', 'ReportsController@exl_defaulters');
	Route::get('/exl_breaches', 'ReportsController@exl_breaches');	
	Route::get('/exl_usershealth', 'ReportsController@exl_usershealth');
	Route::get('/exl_1stdegree/{userid}', 'ReportsController@exl_1stdegree');
	Route::get('/exl_2nddegree/{userid}', 'ReportsController@exl_2nddegree');

});





