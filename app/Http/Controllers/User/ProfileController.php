<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller {
	public function getDiskUsed() {
		return response()->json( diskUsed() );
	}
}
