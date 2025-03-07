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
        Schema::create('employees', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('emp_id', 9)->primary();
            $table->string('emp_name');
            $table->string('emp_position');
            $table->string('date_hired');
            $table->integer('sick_leave');
            $table->integer('vacation_leave');
            $table->timestamp('leave_renewal_date');
            $table->timestamps();
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
        Schema::dropIfExists('employees');
    }
};
