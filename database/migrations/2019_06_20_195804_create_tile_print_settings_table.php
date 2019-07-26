<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTilePrintSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('tile_print_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tile_id');
            $table->unique('tile_id');

            $table->tinyInteger('front_is_printer_friendly')->default(0);
            $table->tinyInteger('front_invert_title')->default(0);
            $table->string('front_cut_line_color')->default('transparent');
            $table->tinyInteger('front_invert_abilities')->default(0);

            $table->tinyInteger('back_is_printer_friendly')->default(0);
            $table->tinyInteger('back_invert_title')->default(0);
            $table->string('back_cut_line_color')->default('transparent');
            $table->tinyInteger('back_invert_bottom_headings')->default(0);
            $table->tinyInteger('back_invert_flavor_text')->default(0);

            $table->foreign('tile_id')
                ->references('id')->on('tiles')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tile_print_settings');
    }
}
