<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Folder;
use Illuminate\Http\Request;

class ContactController extends Controller {

	public function add( Request $request ) {

		$this->validate( $request, [
			'first_name'  => 'required|string|max:255',
			'last_name'   => 'required|string|max:255',
			'email'       => 'required|email',
			'phone'       => 'required|numeric',
			'social_prof' => 'url'
		] );

		$contact = Contacts::create( [
			'user_id'     => auth()->user()->id,
			'first_name'  => $request->first_name,
			'last_name'   => $request->last_name,
			'email'       => $request->email,
			'phone'       => $request->phone,
			'social_prof' => $request->social
		] );


		return response()->json( $contact );

	}

	public function getContacts() {
		$contact = auth()->user()->contacts;

		return response()->json( $contact );
	}

	public function delete( Request $request ) {

		$conD = auth()->user()->contacts->where( 'id', $request->contactId )->first();
		if ( ! $conD ) {
			return;
		}
		$conD->delete();

		return response()->json( [ '200' ] );
	}

	public function update( Request $request ) {
		$this->validate( $request, [
			'first_name'  => 'required|string|max:255',
			'last_name'   => 'required|string|max:255',
			'email'       => 'required|email',
			'phone'       => 'required|numeric',
			'social_prof' => 'url'
		] );

		$contact = Contacts::find( $request->id );
		$contact->update( [
			'first_name'  => $request->first_name,
			'last_name'   => $request->last_name,
			'email'       => $request->email,
			'phone'       => $request->phone,
			'social_prof' => $request->social
		] );

		return response()->json( $contact );
	}

	public function getUnShare( $folderId ) {
		if ( ! ( auth()->user()->folders->find( $folderId ) ) ) {
			return;
		}
		$folderObj       = ( Folders::find( $folderId ) )->access();
		$unshareContacts = Contacts::whereNotIn( 'id', $folderObj->pluck( 'id' ) )->get();

		return response()->json( $unshareContacts );
	}
}
