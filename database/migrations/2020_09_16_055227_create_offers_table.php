<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->string('variant_id');
            $table->string('variant_name');
            $table->string('product_name');
            $table->string('product_id');
            $table->integer('store_id');
            $table->integer('variant_offered_amount');
            $table->integer('variant_actual_amount');
            $table->enum('status', ['pending', 'denied', 'accepted']);
            $table->integer('enable_offer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
