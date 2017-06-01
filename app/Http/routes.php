<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::controller('invitations', 'InvitationsController');
Route::get('/terms-conditions', function(){
	return view('pages.terms-conditions');
});

Route::get('/privacy-policy', function(){
	return view('pages.privacy-policy');
});

Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');
Route::get('logout', 'Auth\AuthController@logout');
Route::controller('password', 'Auth\PasswordController');

Route::get('logout', 'Auth\AuthController@logout');

if (app()->isLocal()) {
	Route::get('register', 'Auth\AuthController@showRegistrationForm');
	Route::post('register', 'Auth\AuthController@register');
}

Route::group(['middleware' => ['auth']], function () {
	Route::get('/home', 'HomeController@index');
	Route::controller('/users', 'UsersController');
	Route::controller('/installers', 'InstallersController');
	Route::controller('/agents', 'AgentsController');
	Route::controller('/products', 'ProductsController');
	Route::controller('/clients', 'ClientsController');
	Route::controller('/contacts', 'ContactsController');
	Route::controller('/pricing', 'PricingController');
	Route::controller('/quotes', 'QuotesController');
	Route::controller('/jobs', 'JobsController');
	Route::controller('/resources', 'ResourcesController');
	Route::controller('/import', 'ImportController');
	Route::controller('/account', 'AccountController');
	Route::controller('/booking_requests', 'BookingRequestsController');
	Route::controller('/quote_requests', 'QuoteRequestsController');
	Route::controller('/service_requests', 'ServiceRequestsController');

	Route::get('/', function() {
		return redirect(Auth::user()->getDashboardUrl());
	});
});


Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function() {
	Route::controller('/dashboard', 'DashboardController');
});

Route::group(['namespace' => 'Agent', 'middleware' => ['auth', 'agent']], function() {
	Route::controller('/dashboard', 'DashboardController');
});

Route::group(['prefix' => 'installer', 'namespace' => 'Installer', 'middleware' => ['auth', 'installer']], function() {
	Route::controller('/dashboard', 'DashboardController');
});

// API routes
Route::group(['prefix' => 'api', 'namespace' => 'Api'], function() {
	// Token not required
	Route::post('auth/authenticate', 'AuthController@postAuthenticate');
	Route::controller('users', 'UsersController');

	// Token required
	Route::group(['middleware' => 'auth:api'], function() {
		Route::controller('account', 'AccountController');
		Route::controller('contacts', 'ContactsController');
		Route::controller('documents', 'DocumentsController');
		Route::controller('form', 'FormController');
		Route::controller('installers', 'InstallersController');
		Route::controller('jobs', 'JobsController');
		Route::controller('notes', 'NotesController');
		Route::controller('products', 'ProductsController');
		Route::controller('resources', 'ResourcesController');
		Route::controller('requests', 'RequestsController');
	});
});
