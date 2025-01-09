<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Helper\SendMail;

class ReminderTimeSheet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:timesheet';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder timesheet for every employee';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $from = 'noreply@mybluethink.in';
        $users = DB::select("SELECT * FROM users WHERE role = 2 OR role = 3 OR role = 4");
        $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'reminder_time_sheet' AND status = '1'");

        if(!empty($users)){
            foreach ($users as $user) {
                $html = str_replace("{{##USERNAME$}}",$user->name,$emailTemplate[0]->content);
                SendMail::sendMail($html, $emailTemplate[0]->subject, $user->email, $from, '');
            }
        }
    }
}
