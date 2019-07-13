<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountsLikesFollowerColumnsInLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            //
			$table->integer('fb_likes')->after('fb_link')->nullable();
			$table->integer('tw_followers')->after('tw_link')->nullable();
			$table->integer('in_followers')->after('in_link')->nullable();
			$table->integer('li_visitors')->after('li_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            //
        });
    }
}
