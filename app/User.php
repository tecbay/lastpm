<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail {
	use Notifiable;
	use HasRoles;


	protected $attributes = [
		'used_spaces'   => 0,
		'plan_id'       => null,
		'is_subscriber' => false,
		'start_at'      => null,
		'end_at'        => null,
	];

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

	public static function boot() {
		parent::boot();
		self::creating( function ( $model ) {
			$model->uuid = (string) Uuid::uuid4()->toString();
		} );
	}

	public function folders() {
		return $this->hasMany( 'App\Models\Folder' );
	}

	public function plan() {
		return $this->hasOne( 'App\Models\Plan' );
	}

	public function contacts() {
		return $this->hasMany( 'App\Models\Contact' );
	}

	public function is_subscriber() {
		return $this->is_subscriber;
	}

	public function is_bill_payer() {
		$subscribe_end_at = new Carbon( $this->end_at );

		return $subscribe_end_at->gt( Carbon::now() );
	}
}
