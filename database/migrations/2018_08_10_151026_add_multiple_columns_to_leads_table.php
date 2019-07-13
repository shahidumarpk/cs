<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMultipleColumnsToLeadsTable extends Migration
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
			$table->text('web_link')->after('user_id')->nullable();
			$table->text('li_link')->after('user_id')->nullable();
			$table->text('in_link')->after('user_id')->nullable();
			$table->text('tw_link')->after('user_id')->nullable();
			$table->text('fb_link')->after('user_id')->nullable();
			$table->tinyinteger('sol_ser')->after('user_id');
			$table->tinyinteger('testimonials')->after('user_id');
			$table->tinyinteger('company_pro')->after('user_id');

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
