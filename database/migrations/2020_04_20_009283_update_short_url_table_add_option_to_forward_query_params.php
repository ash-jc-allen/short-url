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
        Schema::connection(config('short-url.connection'))->table('short_urls', function (Blueprint $table): void {
            $table->boolean('forward_query_params')->after('single_use')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('short-url.connection'))->table('short_urls', function (Blueprint $table): void {
            $table->dropColumn(['forward_query_params']);
        });
    }
}
