<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->uuid('id')->index()->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('video')->nullable();
            $table->string('img');
            $table->bigInteger('category_id');
            $table->boolean('published')->default(false);
            $table->boolean('uploaded_video')->default(false);
            $table->unsignedInteger('video_payment_status_id')->default(1);

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
        Schema::dropIfExists('videos');
    }
}
