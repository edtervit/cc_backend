<?php

use App\Models\Image;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_image_topic', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('image_id');
            $table->unsignedBigInteger('image_topic_id');
            $table->foreign('image_id')->references('id')->on('images')
                ->onDelete('cascade');
            $table->foreign('image_topic_id')->references('id')->on('image_topics')
                ->onDelete('cascade');
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
        Schema::dropIfExists('image_image_topic');
    }
};
