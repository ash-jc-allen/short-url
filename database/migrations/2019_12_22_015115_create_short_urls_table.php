<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('short-url.connection'))->create('short_urls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('destination_url');

            $table->string('url_key')->unique()->when(
                Schema::getConnection()->getConfig('driver') === 'mysql',
                function (Blueprint $column) {
                    $column->collation('utf8mb4_bin');
                }
            );

            $table->string('default_short_url');
            $table->boolean('single_use');
            $table->boolean('track_visits');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('short-url.connection'))->dropIfExists('short_urls');
    }
}
