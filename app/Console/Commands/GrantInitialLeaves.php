<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use Carbon\Carbon;

class GrantInitialLeaves extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaves:grant-initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant initial 5 sick and 5 vacation leaves after six months from date hired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        // Employees who are at least 6 months from date_hired and still have 0 balances
        $eligibleEmployees = Employee::query()
            ->whereDate('date_hired', '<=', $now->copy()->subMonthsNoOverflow(6)->toDateString())
            ->where(function ($q) {
                $q->where('sick_leave', 0)->orWhereNull('sick_leave');
            })
            ->where(function ($q) {
                $q->where('vacation_leave', 0)->orWhereNull('vacation_leave');
            })
            ->get();

        foreach ($eligibleEmployees as $employee) {
            $employee->sick_leave = 5;
            $employee->vacation_leave = 5;
            $employee->save();
        }

        $this->info('Processed ' . $eligibleEmployees->count() . ' employees for initial leave grant.');

        return Command::SUCCESS;
    }
}


