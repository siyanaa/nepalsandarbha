<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShareCountsToPostsTable extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->integer('share_count_facebook')->default(0);
            $table->integer('share_count_twitter')->default(0);
            $table->integer('share_count_viber')->default(0);
            $table->integer('share_count_whatsapp')->default(0);
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['share_count_facebook', 'share_count_twitter', 'share_count_viber', 'share_count_whatsapp']);
        });
    }
}
