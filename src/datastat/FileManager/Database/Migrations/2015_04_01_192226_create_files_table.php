<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->increments('id');
            
            $table->jsonb('name');
            $table->jsonb('description');
            $table->integer('folder_id');

            $table->string('type', 45)->nullable(); // image, document
            $table->string('mime_type', 100)->nullable(); // application/json, image/png, ...
            $table->jsonb('file_info');
            $table->string('disk', 100); // disk used, if needed
            
            $table->string('location_path', 250); // path on cloud
            $table->string('public_url', 200);
            
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
		});

		Schema::create('fileables', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('file_id');
            $table->foreign('file_id')->references('id')->on('files')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->integer('fileable_id');
            $table->string('fileable_type', 200);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('fileables');
        Schema::drop('files');
	}

}
