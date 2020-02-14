<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShortURLVisitsTableForVersionTwoZeroZero extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_url_visits', function (Blueprint $table) {
            $table->string('referer_url')->after('browser_version')->nullable();
            $table->string('device_type')->after('referer_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('short_url_visits', function (Blueprint $table) {
            $table->dropColumn(['referer_url', 'device_type']);
        });
    }
}
