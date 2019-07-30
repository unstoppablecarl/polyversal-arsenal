<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassColumnToWeaponsTable extends Migration
{
    public function up()
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->integer('class')->after('display_name');
        });
    }

    public function down()
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->dropColumn('class');
        });
    }
}
