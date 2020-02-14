<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateShortURLTableForVersionTwoZeroZero extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->integer('redirect_status_code')->after('track_visits')->default(301);
            $table->boolean('track_ip_address')->after('redirect_status_code')->default(false);
            $table->boolean('track_operating_system')->after('track_ip_address')->default(false);
            $table->boolean('track_operating_system_version')->after('track_operating_system')->default(false);
            $table->boolean('track_browser')->after('track_operating_system_version')->default(false);
            $table->boolean('track_browser_version')->after('track_browser')->default(false);
            $table->boolean('track_referer_url')->after('track_browser_version')->default(false);
            $table->boolean('track_device_type')->after('track_referer_url')->default(false);
        });

        DB::table('short_urls')->update([
            'track_ip_address'               => config('short-url.tracking.fields.ip_address'),
            'track_operating_system'         => config('short-url.tracking.fields.operating_system'),
            'track_operating_system_version' => config('short-url.tracking.fields.operating_system_version'),
            'track_browser'                  => config('short-url.tracking.fields.browser'),
            'track_browser_version'          => config('short-url.tracking.fields.browser_version'),
            'track_referer_url'              => config('short-url.tracking.fields.referer_url'),
            'track_device_type'              => config('short-url.tracking.fields.device_type'),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->dropColumn([
                'redirect_status_code',
                'track_ip_address',
                'track_operating_system',
                'track_operating_system_version',
                'track_browser',
                'track_browser_version',
                'track_referer_url',
                'track_device_type',
            ]);
        });
    }
}
