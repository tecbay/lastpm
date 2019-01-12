<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail {
	use Notifiable;
	use HasRoles;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	public function folders() {
		return $this->hasMany( 'App\Models\Folder' );
	}

	public function contacts() {
		return $this->hasMany('App\Models\Contact');
	}

	public static function boot() {
		parent::boot();
		self::creating( function ( $model ) {
			$model->uuid = (string) Uuid::uuid4()->toString();
		} );
	}

}
