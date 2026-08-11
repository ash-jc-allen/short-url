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
        Schema::connection(config('short-url.connection'))->table('short_urls', function (Blueprint $table) {
            $table->timestamp('activated_at')->after('track_device_type')->nullable()->default(now());
            $table->timestamp('deactivated_at')->after('activated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('short-url.connection'))->table('short_urls', function (Blueprint $table): void {
            $table->dropColumn(['activated_at', 'deactivated_at']);
        });
    }
}
