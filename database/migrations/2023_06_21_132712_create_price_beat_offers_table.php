<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_beat_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('store_id');
            $table->unsignedBigInteger('variant_id');
            $table->string('variant_name');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->float('offered_amount')->nullable();
            $table->float('actual_amount');
            $table->string('competitor_url');
            $table->char('status', 100);
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
        Schema::dropIfExists('price_beat_offers');
    }
};
