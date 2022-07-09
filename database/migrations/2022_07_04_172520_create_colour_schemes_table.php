<?php

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
        Schema::create('colour_schemes', function (Blueprint $table) {
            $table->id();
            $table->tinyText('colour_1');
            $table->tinyText('colour_2');
            $table->tinyText('colour_3');
            $table->tinyText('colour_4');
            $table->tinyText('colour_5');
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
        Schema::dropIfExists('colour_schemes');
    }
};
