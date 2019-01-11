<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
	        $table->increments('id');
	        $table->unsignedBigInteger('folder_id');
	        $table->unsignedBigInteger('file_size');
	        $table->string('name');
	        $table->string('url');
	        $table->string('path');
	        $table->char('storage_type',128);
	        $table->string('type')->nullable();
	        $table->string('extension')->nullable();
	        $table->char('mime',100);
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
        Schema::dropIfExists('files');
    }
}
