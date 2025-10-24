<?php

namespace App\Console\Commands;

use App\Mail\SendRemainderMailLoopNumberRequest;
use App\Models\InstrumentIndex;
use Illuminate\Console\Command;
use App\Models\LoopNumberRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApprovedReminderMail;
use Illuminate\Support\Facades\Log;

class DailyTask extends Command
{
    protected $signature = 'daily:task';
    protected $description = 'Send email reminders for approved loop number requests';

    public function handle()
    {
        // find all loop number request that ticket number is not exist in instrument index
        // find all approved loop number request in the last month
        $oneMonthAgo = now()->subMonth()->startOfDay();

        $approvedRequests = LoopNumberRequest::with('engineers')
            ->where('status', 'approve')
            ->where('created_at', '<=', $oneMonthAgo)
//            ->whereDoesntHave('instruments', function ($query) {
//                $query->where('is_finalize', false);
//            })
            ->where('is_completed', false)
            ->get();

        $this->info($oneMonthAgo);
        if ($approvedRequests->isEmpty()) {
            $this->info('No approved requests found.');
            return;
        }

        foreach ($approvedRequests as $request) {
            $this->info($oneMonthAgo);
            $this->info('Emails sent successfully to approved requestors. ' . $request->engineers->email . 'Ticket : ' . $request->ticket_number);
            if ($request->engineers->email) { // make sure requestor has email
                Mail::to($request->engineers->email)->send(new SendRemainderMailLoopNumberRequest($request));
            }
        }

        Log::info('Sent approved reminders at ' . now() . ' for ' . $approvedRequests->count() . ' requestors.');
        $this->info('Emails sent successfully to approved requestors.');
    }
}
