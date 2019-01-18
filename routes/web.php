<?php
//todo : shoud remove in Production
Route::get( '/ajaxtest', function () {


	dd( config( 'app.name' ) );

//	$folder->viewers()->attach([1,2]);

	dd( $folder->addViewers( [ 1, 2 ] ) );

//dd($folder->removeViewers([1,2]));

	return view( 'ajaxtest' );
} );

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
Route::group( [
	'prefix'     => LaravelLocalization::setLocale(),
	'middleware' => [
		'auth',
		'localeSessionRedirect',
		'localizationRedirect',
		'localeViewPath',
		'localize'
	]
], function () {
	//+++++++++++++++Admin Route Groupe++++++++++++++++++++++++++
	//<editor-fold desc="Admin Route">
	Route::group( [
		'namespace'  => 'admin',
		'middleware' => [ 'role:admin' ],
		'as'         => 'admin.',
		'prefix'     => 'admin'
	], function () {
		// All Admin Routes +++++
		Route::get( '/', 'HomeController' )->name( 'home' );

	} );
	//</editor-fold>
	//+++++++++++++++++++++User Route Groupe+++++++++++++++++++++++
	//<editor-fold desc="User Route">
	Route::group( [
		'namespace'  => 'User',
		'middleware' => [ 'role:user', 'verified' ]
	], function () {

		Route::get( '/drive', 'HomeController' )->name( 'user.home' );
//		Route::get( LaravelLocalization::transRoute( 'routes.posts' ), function () {
//			return view( 'home' );
//		} )->name( 'post.all' );


		Route::get( 'disk-used', 'ProfileController@getDiskUsed' )->name( 'disk.used' );

		//File Controller

		Route::post( 'file', 'FileController@store' )->name( 'file.upload' );
		Route::post( 'delete/file/{id}', 'FileController@destroy' )->name( 'file.delete' );

		Route::get( 'contacts', 'ContactController@getContacts' )->name( 'contacts' );

		// Folder Controller
		Route::get( 'folder', 'FolderController@index' )->name( 'folders' );
		Route::post( 'folder', 'FolderController@store' )->name( 'folder.create' );
		Route::get( 'folder/{id}', 'FolderController@show' );
		Route::post( 'delete/folder/{id}', 'FolderController@destroy' );
		Route::post( 'add-viewers', 'FolderController@addViewers' )->name( 'folder.viewers.add' );
		Route::post( 'remove-viewers', 'FolderController@removeViewers' )->name( 'folder.viewers.remove' );


		Route::get( 'subscribe', 'SubscriptionController@subscribe' );
		Route::post( 'subscribe', 'SubscriptionController@confirmSubscribe' )->name( 'subscribe.confirm' );
		Route::get( 'upgrade', 'SubscriptionController@upgrade' );
		Route::post( 'upgrade', 'SubscriptionController@confirmUpgrade' )->name( 'upgrade.confirm' );
		Route::get( 'billpay', 'SubscriptionController@billpay' );
		Route::post( 'billpay', 'SubscriptionController@confirmBillpay' )->name( 'billpay.confirm' );


	} );
	//</editor-fold>


	//<editor-fold desc="Common routes">
	Route::group( [
		'middleware' => [ 'role:user' ]
	], function () {


//		Route::resource('folder', 'FolderController',
//			['except' => ['create', 'store', 'update', 'destroy']]);
	} );
	//</editor-fold>
} );

