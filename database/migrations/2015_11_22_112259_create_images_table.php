<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gallery_id')->nullable();
            $table->unsignedBigInteger('image_driver_id');
            $table->unsignedBigInteger('uploaded_user_id');
            $table->string('name')->nullable();
            $table->string('type')->default('url');
            $table->longText('path');
            $table->string('fileID')->nullable();
            $table->string('thumbnailUrl')->nullable();
            $table->bigInteger('size')->nullable();
            $table->bigInteger('width')->nullable();
            $table->bigInteger('height')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');
            $table->foreign('image_driver_id')->references('id')->on('image_drivers')->onDelete('cascade');
            $table->foreign('uploaded_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
