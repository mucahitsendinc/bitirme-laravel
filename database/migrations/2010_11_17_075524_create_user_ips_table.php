<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ips', function (Blueprint $table) {
            $table->id();
            $table->string('register_ip')->nullable();
            $table->timestamp('register_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('last_login_ip')->nullable();
            $table->string('last_unsuccessful_login_ip')->nullable();
            $table->timestamp('last_unsuccessful_login_date')->nullable();
            $table->string('last_request_ip')->nullable();
            $table->timestamp('last_request_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('last_unsuccesful_request_ip')->nullable();
            $table->timestamp('last_unsuccesful_request_date')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_ips');
    }
}
