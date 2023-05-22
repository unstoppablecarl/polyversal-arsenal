<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsBuildingColumnToWeaponsTable extends Migration
{
    public function up()
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->tinyInteger('is_building')->nullable()->default(null)->after('is_infantry');
        });
        Schema::table('abilities', function (Blueprint $table) {
            $table->tinyInteger('building_valid')->nullable()->default(null)->after('cavalry_valid');
        });
    }

    public function down()
    {
        Schema::table('weapons', function (Blueprint $table) {
            $table->dropColumn('is_building');
        });
        Schema::table('abilities', function (Blueprint $table) {
            $table->dropColumn('building_valid');
        });
    }
}
