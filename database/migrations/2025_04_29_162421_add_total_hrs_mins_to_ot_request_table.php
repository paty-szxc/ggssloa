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
        Schema::table('ot_request', function (Blueprint $table) {
            $table->string('total_hrs_mins')->nullable()->after('time_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ot_request', function (Blueprint $table) {
            $table->dropColumn('total_hrs_mins');
        });
    }
};
