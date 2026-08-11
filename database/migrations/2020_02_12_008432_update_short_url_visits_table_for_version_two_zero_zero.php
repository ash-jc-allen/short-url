<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection(config('short-url.connection'))->table('short_url_visits', function (Blueprint $table): void {
            $table->string('referer_url')->after('browser_version')->nullable();
            $table->string('device_type')->after('referer_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::connection(config('short-url.connection'))->table('short_url_visits', function (Blueprint $table): void {
            $table->dropColumn(['referer_url', 'device_type']);
        });
    }
}
