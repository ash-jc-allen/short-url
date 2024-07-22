<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShortUrlTableMakeUrlKeyNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('short-url.connection'))->table('short_urls', function (Blueprint $table) {
            $table->string('url_key')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('short-url.connection'))->table('short_urls', function (Blueprint $table) {
            $table->string('url_key')->nullable(false)->change();
        });
    }
}
