<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transections', function (Blueprint $table) {
	        $table->increments( 'id' );
	        $table->unsignedBigInteger( 'user_id' );
	        $table->float( 'amount' );
	        $table->char( 'type',64 ); // generally three 1.subscribe 2.billpay 3. upgrade
	        $table->string( 'payer_id' );
	        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transections');
    }
}
