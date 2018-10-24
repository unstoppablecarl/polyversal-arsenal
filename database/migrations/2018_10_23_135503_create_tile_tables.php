<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTileTables extends Migration
{
    public function up()
    {
        Schema::create('tile_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('tiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('tile_type_id');
            $table->foreign('tile_type_id')->references('id')->on('tile_types');

            $table->timestamps();
        });

        Schema::create('weapons', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('slug')->unique();
            $table->string('name');

            $table->string('ap');
            $table->string('at');
            $table->string('aa');
            $table->integer('range');

            $table->string('damage');
            $table->tinyInteger('is_ama')->default(0);
            $table->tinyInteger('is_indirect')->default(0);

            $table->timestamps();
        });

        Schema::create('arcs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('direction');
            $table->integer('size');
        });

        Schema::create('tile_weapon_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('tile_weapons', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('tile_id');
            $table->foreign('tile_id')->references('id')->on('tiles');

            $table->unsignedInteger('weapon_id');
            $table->foreign('weapon_id')->references('id')->on('weapons');

            $table->unsignedInteger('arc_id');
            $table->foreign('arc_id')->references('id')->on('arcs');

            $table->unsignedInteger('tile_weapon_type_id');
            $table->foreign('tile_weapon_type_id')->references('id')->on('tile_weapon_types');

            $table->integer('quantity');
            $table->integer('display_order');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tile_weapons');
        Schema::dropIfExists('arcs');
        Schema::dropIfExists('tiles');
        Schema::dropIfExists('weapons');
    }
}
