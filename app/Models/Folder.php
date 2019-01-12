<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model {
	use HasViewers;
	/**
	 * The model's default values for attributes.
	 *
	 * @var array
	 */
	protected $attributes = [
		'folder_size' => 0
	];

	protected $fillable = [
		'name',
		'path',
		'password',
	];
	protected $hidden = [
		'user_id'
	];


	public function files() {
		return $this->hasMany( 'App\Models\File' );
	}

	public function viewers() {
		return $this->morphToMany( 'App\Models\Contact', 'contactable' );
	}

	public static function boot() {
		parent::boot();
		self::deleted( function ( $model ) {
		} );
	}
}
