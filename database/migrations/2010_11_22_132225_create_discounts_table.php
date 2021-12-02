<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('percent');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('coupon')->nullable()->unique();
            $table->integer('max_uses')->nullable();
            $table->integer('uses')->default(0);
            $table->integer('max_uses_user')->nullable();
            $table->integer('max_discount_amount')->nullable();
            $table->integer('max_discount_amount_user')->nullable();
            $table->integer('min_order_amount')->nullable();
            $table->integer('active')->default(1);
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
        Schema::dropIfExists('discounts');
    }
}
