<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Loan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLoanReturnReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:send-loan-return-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $loansDue = Loan::where('delivery_date', Carbon::now()->addDay())
            ->where('status', 'open')
            ->get();

        foreach ($loansDue as $loan) {
            $user = $loan->user; // Replace with your user relation method
            $loanDetails = $loan->toArray(); // Replace with relevant loan details

            Mail::to($user->email)->send(new LoanReturnReminderEmail($loanDetails));
        }

        return 0;
    }
}
