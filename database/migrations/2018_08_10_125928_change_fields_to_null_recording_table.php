<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsToNullRecordingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recording', function (Blueprint $table) {
            //
			$table->text('title')->nullable()->change();
			$table->text('link')->nullable()->change();
			$table->text('note')->nullable()->change();
			$table->text('recording_file')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recording', function (Blueprint $table) {
            //
        });
    }
}
