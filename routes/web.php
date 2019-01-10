<?php


Route::group( [
	'prefix'     => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'localize' ]
], function () {
	Auth::routes( [ 'verify' => true ] );
} );

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
		'middleware' => [ 'role:user', 'verified' ]
	], function () {
		//++++ Localized Route Group
		Route::group( [
			'localeSessionRedirect',
			'localizationRedirect',
			'localeViewPath',
			'localize'
		], function () {
			Route::get( '/drive', 'HomeController' )->name( 'user.home' );
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

