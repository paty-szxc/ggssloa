<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('official_business', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->unsignedBigInteger('user_id');
            $table->string('emp_name'); // Employee name
            $table->date('date'); // Date of travel
            $table->string('destination'); // Travel destination
            $table->string('purpose'); // Purpose of travel
            $table->time('time_departure'); // Departure time
            $table->time('time_return')->nullable(); // Return time
            $table->integer('status')->default(0);
            $table->string('approved_by')->nullable();
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('official_business');
    }
};
