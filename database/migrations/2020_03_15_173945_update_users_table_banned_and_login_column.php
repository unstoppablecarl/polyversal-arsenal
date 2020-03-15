<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTableBannedAndLoginColumn extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('banned_at', 'is_banned');
            $table->timestamp('last_login_at')->after('remember_token')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('is_banned', 'banned_at');
            $table->dropColumn('last_login_at');
        });
    }
}
