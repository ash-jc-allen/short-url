<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShortUrlTableAddOptionToForwardQueryParams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('short-url.connection'))->table(config('short-url.urls_table'), function (Blueprint $table) {
            $table->boolean('forward_query_params')->after('single_use')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('short-url.connection'))->table(config('short-url.urls_table'), function (Blueprint $table) {
            $table->dropColumn(['forward_query_params']);
        });
    }
}
