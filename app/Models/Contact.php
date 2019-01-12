<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
	/**
	 * Get all of the posts that are assigned this tag.
	 */
	public function folsers()
	{
		return $this->morphedByMany('App\Models\Folder', 'contacts');
	}

}
