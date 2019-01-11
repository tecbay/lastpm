<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFolder;
use App\Models\Folder;
use App\User;
use Illuminate\Http\Request;

class FolderController extends Controller {

	public function __construct() {
		$this->middleware( 'CheckFolderViewPermission' )->only( 'index' );
//		$this->middleware( 'CheckUploadPermission' )->only( 'store' );
		$this->middleware( 'CheckDeletePermission' )->only( 'destroy' );
		$this->middleware( 'CheckEditPermission' )->only( 'update' );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		return response()->json( auth()->user()->folders );
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( CreateFolder $request ) {
		//
		dd( $request->validated());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( Folder $folder ) {

		return response()->json( $folder->files );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( Folder $folder ) {

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, Folder $folder ) {
		$folder->update( [] );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( $id ) {
		//
	}

	public function addViewers() {

	}
}
