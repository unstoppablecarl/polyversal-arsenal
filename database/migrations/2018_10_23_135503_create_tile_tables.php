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
            $table->string('icon')->nullable();
            $table->string('display_name');
            $table->integer('display_order')->nullable();

            $table->integer('cost_static')->nullable();

            $table->integer('cost_infantry')->nullable();
            $table->integer('cost_cavalry')->nullable();

            $table->integer('cost_vehicle_class_1')->nullable();
            $table->integer('cost_vehicle_class_2')->nullable();
            $table->integer('cost_vehicle_class_3')->nullable();
            $table->integer('cost_vehicle_class_4')->nullable();
            $table->integer('cost_vehicle_class_5')->nullable();

            $table->tinyInteger('infantry_valid')->nullable();
            $table->tinyInteger('cavalry_valid')->nullable();
            $table->tinyInteger('vehicle_valid')->nullable();

            $table->tinyInteger('is_defensive')->nullable();
            $table->decimal('warhead_cost_multiplier')->nullable();
        });

        Schema::create('anti_missile_systems', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('display_name');
            $table->integer('cost');
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

        Schema::create('chassis', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('tile_type_id');
            $table->foreign('tile_type_id')->references('id')->on('tile_types');

            $table->unsignedInteger('tile_class_id');
            $table->foreign('tile_class_id')->references('id')->on('tile_classes');

            $table->unsignedInteger('tech_level_id');
            $table->foreign('tech_level_id')->references('id')->on('tech_levels');

            $table->unsignedInteger('mobility_id');
            $table->foreign('mobility_id')->references('id')->on('mobilities');

            $table->integer('cost');
            $table->integer('evasion');

            $table->integer('damage_stress')->nullable();
            $table->integer('damage_immobilized')->nullable();
            $table->integer('damage_weapon_destroyed')->nullable();
            $table->integer('damage_targeting_destroyed')->nullable();
            $table->integer('damage_hull_breach')->nullable();
            $table->integer('damage_fuel_leak')->nullable();
            $table->integer('damage_destroyed')->nullable();
        });

        Schema::create('chassis_armor_stats', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('chassis_id');
            $table->foreign('chassis_id')->references('id')->on('chassis');

            $table->integer('armor');
            $table->integer('move');
            $table->integer('cost');
        });

        Schema::create('tiles', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('chassis_id');
            $table->foreign('chassis_id')->references('id')->on('chassis');

            $table->unsignedInteger('anti_missile_system_id');
            $table->foreign('anti_missile_system_id')->references('id')->on('anti_missile_systems');

            $table->unsignedInteger('targeting_id');
            $table->foreign('targeting_id')->references('id')->on('combat_values');

            $table->unsignedInteger('assault_id');
            $table->foreign('assault_id')->references('id')->on('combat_values');

            $table->string('name');

            $table->integer('armor');
            $table->integer('stealth');

            $table->string('front_source_image')->nullable();
            $table->string('back_source_image')->nullable();

            $table->string('front_svg')->nullable();
            $table->string('front_image')->nullable();
            $table->string('front_thumb')->nullable();

            $table->string('back_svg')->nullable();
            $table->string('back_image')->nullable();
            $table->string('back_thumb')->nullable();

            $table->integer('cached_cost');

            $table->text('flavor_text')->nullable();

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

            $table->tinyInteger('is_infantry')->nullable();
            $table->tinyInteger('is_indirect')->nullable();
            $table->tinyInteger('has_warheads')->nullable();

            $table->integer('cost_d4')->nullable();
            $table->integer('cost_d6')->nullable();
            $table->integer('cost_d8')->nullable();
            $table->integer('cost_d10')->nullable();
            $table->integer('cost_d12')->nullable();

            $table->timestamps();
        });

        Schema::create('tile_abilities', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('tile_id');
            $table->foreign('tile_id')->references('id')->on('tiles')->onDelete('cascade');

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
            $table->foreign('tile_id')->references('id')->on('tiles')->onDelete('cascade');

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
        Schema::dropIfExists('tile_weapon_types');
        Schema::dropIfExists('tile_abilities');
        Schema::dropIfExists('weapons');
        Schema::dropIfExists('tiles');
        Schema::dropIfExists('chassis_armor_stats');
        Schema::dropIfExists('chassis');
        Schema::dropIfExists('tile_classes');
        Schema::dropIfExists('tech_levels');
        Schema::dropIfExists('mobilities');
        Schema::dropIfExists('combat_values');
        Schema::dropIfExists('arc_sizes');
        Schema::dropIfExists('arc_directions');

        Schema::dropIfExists('anti_missile_systems');
        Schema::dropIfExists('abilities');
        Schema::dropIfExists('tile_types');
    }
}
