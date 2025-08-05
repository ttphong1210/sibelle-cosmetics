<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddResetPasswordExpiresAtToVpUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vp_users', function (Blueprint $table) {
            $table->timestamp('reset_password_expires_at')->nullable()->after('forgot_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vp_users', function (Blueprint $table) {
            $table->dropColumn('reset_password_expires_at');
        });
    }
}
