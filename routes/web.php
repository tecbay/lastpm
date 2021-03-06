<?php

//<editor-fold desc="Login Routes">
Route::get( 'password/reset/{token}', 'Auth\ResetPasswordController@showResetForm' );
Route::group( [
	'prefix'     => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'localize' ]
], function () {
	// Authentication Routes...
	Route::get( 'login', 'Auth\LoginController@showLoginForm' );
	// Registration Routes...
	Route::get( 'register', 'Auth\RegisterController@showRegistrationForm' )->name( 'register' );
	// Password Reset Routes...
	Route::get( 'password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm' );
} );
Route::post( 'login', 'Auth\LoginController@login' )->name( 'login' );
Route::post( 'register', 'Auth\RegisterController@register' )->name( 'register' );
Route::post( 'password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail' );
Route::post( 'password/reset', 'Auth\ResetPasswordController@reset' );
Route::post( 'logout', 'Auth\LoginController@logout' )->name( 'logout' );
//</editor-fold>
// +++++++++++++++++++++Guest route group+++++++++++++++++++++++++++++++++++++++++++++++++++
//<editor-fold desc="Guest Routes group">
Route::group( [
	'namespace'  => 'Guest',
	'middleware' => [ 'guest' ],
	'prefix'     => LaravelLocalization::setLocale()
], function () {
	// All Guest routes
	Route::get( '/', 'HomeController' );
} );
//</editor-fold>

// +++++++++++++++++++++Auth route group++++++++++++++++++++++++++++++++++++++++++++++++++++
Route::group( [ 'middleware' => [ 'auth' ] ], function () {
	//+++++++++++++++Admin Route Groupe++++++++++++++++++++++++++
	//<editor-fold desc="Admin Route">
	Route::group( [
		'namespace'  => 'admin',
		'middleware' => [ 'role:admin' ],
		'as'         => 'admin.'
	], function () {
		// All Admin Routes +++++
		Route::get( '/admin', 'HomeController' )->name( 'home' );

	} );
	//</editor-fold>
	//+++++++++++++++++++++User Route Groupe+++++++++++++++++++++++
	//<editor-fold desc="User Route">
	Route::group( [
		'namespace'  => 'User',
		'prefix'     => LaravelLocalization::setLocale(),
		'middleware' => [ 'role:user' ]
	], function () {
		//++++ Localized Route Group
		Route::group( [
			'localeSessionRedirect',
			'localizationRedirect',
			'localeViewPath',
			'localize'
		], function () {
			Route::get( '/user', 'HomeController' )->name( 'user.home' );
			Route::get( LaravelLocalization::transRoute( 'routes.posts' ), function () {
				return view( 'home' );
			} )->name( 'post.all' );

			Route::get( '/post/1', function ( $as ) {
				return view( 'home' );
			} )->name( 'post' );
		} );
		//++++ Localized Route Group xxxxxx End



	} );
	//</editor-fold>
} );

