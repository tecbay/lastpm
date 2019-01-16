<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsedSpaceToUsersTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table( 'users', function ( Blueprint $table ) {
			$table->unsignedBigInteger( 'used_spaces' )->after( 'uuid' ); // initial value == 0 (define in User Model)
			$table->unsignedSmallInteger( 'plan_id' )->after( 'uuid' )->nullable();
			$table->boolean( 'is_subscriber' )->after( 'uuid' )->default( false );
			$table->dateTime( 'start_at' )->after( 'uuid' )->nullable();
			$table->dateTime( 'end_at' )->after( 'uuid' )->nullable();
		} );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table( 'users', function ( Blueprint $table ) {
			//
		} );
	}
}
