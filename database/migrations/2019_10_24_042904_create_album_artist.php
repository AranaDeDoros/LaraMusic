<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumArtist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('album_artist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('artist_id');
             $table->foreign('artist_id')->references('id')->on('artists')->onDelete('cascade');
            $table->unsignedBigInteger('album_id');
            $table->foreign('album_id')->references('id')->on('albums')->onDelete('cascade');
           
            $table->tinyInteger('listened')->default(0); //0 indicates it's still pending
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('album_artist');
    }
}
