<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateShortUrlVisitsTableConvertFalseValuesToNull extends Migration
{
    public function up(): void
    {
        DB::transaction(static function (): void {
            DB::connection(config('short-url.connection'))
                ->table('short_url_visits')
                ->update([
                    'ip_address' => DB::raw("CASE WHEN ip_address = '0' THEN NULL ELSE ip_address END"),
                    'operating_system' => DB::raw("CASE WHEN operating_system = '0' THEN NULL ELSE operating_system END"),
                    'operating_system_version' => DB::raw("CASE WHEN operating_system_version = '0' THEN NULL ELSE operating_system_version END"),
                    'browser' => DB::raw("CASE WHEN browser = '0' THEN NULL ELSE browser END"),
                    'browser_version' => DB::raw("CASE WHEN browser_version = '0' THEN NULL ELSE browser_version END"),
                    'referer_url' => DB::raw("CASE WHEN referer_url = '0' THEN NULL ELSE referer_url END"),
                    'device_type' => DB::raw("CASE WHEN device_type = '0' THEN NULL ELSE device_type END"),
                ]);
        });
    }
}
