<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCostMultiplierColumnToArcSizesTable extends Migration
{
    public function up()
    {
        Schema::table('arc_sizes', function (Blueprint $table) {
            $table->decimal('cost_multiplier');
        });
    }

    public function down()
    {
        Schema::table('arc_sizes', function (Blueprint $table) {
            $table->dropColumn('cost_multiplier');
        });
    }
}
