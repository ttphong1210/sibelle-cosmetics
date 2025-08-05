<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->integer('inventory_quantity')->default(0);
            $table->integer('reserved_quantity')->default(0);
            $table->integer('available_quantity')->virtualAs('inventory_quantity - reserved_quantity');
            $table->timestamps();

            $table->foreign('product_id')->references('prod_id')->on('products')->onDelete('cascade');
            $table->unique('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventory');
    }
}
