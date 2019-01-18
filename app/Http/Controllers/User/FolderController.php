<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFolder;
use App\Http\Requests\ShowFolder;
use App\Models\Folder;
use App\Repositories\FolderRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FolderController extends Controller {


	public function __construct() {

//		$this->middleware( 'CheckFolderViewPermission' )->only( 'index' );
////		$this->middleware( 'CheckUploadPermission' )->only( 'store' );
//		$this->middleware( 'CheckDeletePermission' )->only( 'destroy' );
//		$this->middleware( 'CheckEditPermission' )->only( 'update' );
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

		$user      = auth()->user();
		$directory = $user->uuid . '/' . hash( 'CRC32', $request->folder_name . date( "Y-m-d H:i:s" ) );
		Storage::makeDirectory( $directory );
		$inputs         = $request->validated();
		$inputs['path'] = $directory;

		$folder = auth()->user()
		                ->folders()
		                ->save( new Folder( $inputs ) );

		$folder->addViewers( [ 1, 2 ] );

		return response()->json( $folder );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( $id ) {

		return response()->json( auth()->user()->folders()->findOrfail( $id )->files );
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
		$folder = auth()->user()->folders()->findOrfail( $id );
		Storage::deleteDirectory( $folder->path );
		$folder->viewers()->detach();
		$folder->files()->delete();
		$folder->delete();

		//
	}

	public function addViewers() {

	}

	public function removeViewers() {

	}
}
