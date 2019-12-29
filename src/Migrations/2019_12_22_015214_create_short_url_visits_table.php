<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortUrlVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('short_url_visits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('short_url_id');
            $table->string('ip_address');
            $table->string('operating_system');
            $table->string('operating_system_version');
            $table->string('browser');
            $table->string('browser_version');
            $table->timestamp('visited_at');
            $table->timestamps();

            $table->foreign('short_url_id')->references('id')->on('short_urls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('short_url_visits');
    }
}
