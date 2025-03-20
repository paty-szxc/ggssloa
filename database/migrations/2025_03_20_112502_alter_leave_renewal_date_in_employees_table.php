<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Get the current year
        $currentYear = date('Y');
        // Set the new leave renewal date to February 1 of the following year
        $newLeaveRenewalDate = date('Y-m-d H:i:s', strtotime("February 1, " . ($currentYear + 1)));

        // Update the leave_renewal_date for all employees
        DB::table('employees')->update(['leave_renewal_date' => $newLeaveRenewalDate]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Optionally, you can implement logic to revert the changes if needed
        // For example, you could set leave_renewal_date back to null or a default value
    }
};