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
        Schema::table('official_business', function (Blueprint $table) {
            $table->string('time_departure')->change();
            $table->string('time_return')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('official_business', function (Blueprint $table) {
            $table->dropColumn('time_departure');
            $table->dropColumn('time_return');
        });
    }
};
