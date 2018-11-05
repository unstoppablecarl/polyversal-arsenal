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

        Schema::create('abilities', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('anti_missile_systems', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('arc_directions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('arc_sizes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('combat_values', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('mobilities', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('tech_levels', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('tile_classes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
        });

        Schema::create('tiles', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('tile_type_id');
            $table->foreign('tile_type_id')->references('id')->on('tile_types');

            $table->unsignedInteger('tile_class_id');
            $table->foreign('tile_class_id')->references('id')->on('tile_classes');

            $table->unsignedInteger('targeting_id');
            $table->foreign('targeting_id')->references('id')->on('combat_values');

            $table->unsignedInteger('assault_id');
            $table->foreign('assault_id')->references('id')->on('combat_values');

            $table->unsignedInteger('tech_level_id');
            $table->foreign('tech_level_id')->references('id')->on('tech_levels');

            $table->unsignedInteger('mobility_id');
            $table->foreign('mobility_id')->references('id')->on('mobilities');

            $table->unsignedInteger('anti_missile_system_id');
            $table->foreign('anti_missile_system_id')->references('id')->on('anti_missile_systems');

            $table->string('name');

            $table->decimal('app_version');

            $table->integer('armor');
            $table->integer('stealth');
            $table->json('tile_data')->nullable();

            $table->integer('cached_cost');

            $table->timestamps();
        });

        Schema::create('weapons', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('name');
            $table->string('display_name');

            $table->string('ap');
            $table->string('at');
            $table->string('aa');
            $table->integer('range');

            $table->string('damage');
            $table->tinyInteger('is_ama')->default(0);
            $table->tinyInteger('is_indirect')->default(0);
            $table->tinyInteger('has_warheads')->default(0);

            $table->timestamps();
        });

        Schema::create('tile_abilities', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('tile_id');
            $table->foreign('tile_id')->references('id')->on('tiles');

            $table->unsignedInteger('ability_id');
            $table->foreign('ability_id')->references('id')->on('abilities');
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

            $table->unsignedInteger('arc_size_id');
            $table->foreign('arc_size_id')->references('id')->on('arc_sizes');

            $table->unsignedInteger('arc_direction_id');
            $table->foreign('arc_direction_id')->references('id')->on('arc_directions');

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
