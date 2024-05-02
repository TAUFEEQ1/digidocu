<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Subscription;

class TriggerCallback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trigger-callback {ref} {status} {notes}';

    private Subscription $subscription;
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private function setStatus(array $tx){
        switch($tx["status"]){
            case "COMPLETE":
                $this->subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $this->subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $current_date = now();
                $this->subscription->sub_start_date = $current_date;
                $this->subscription->sub_end_date = $current_date->addYear(1);
                $this->subscription->save();
                break;
            case "FAILED":
                $this->subscription->sub_payment_status = config("constants.SUB_PAY_STATES.COMPLETED");
                $this->subscription->status = config("constants.SUB_STATUSES.ACTIVE");
                $this->subscription->sub_payment_notes = $tx["notes"];
                $this->subscription->save();
                break;
            default:
                break;
        }
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ref = $this->argument('ref');
        $status = $this->argument('status');
        $notes = $this->argument('notes');

        $data = ["status"=>$status,"notes"=>$notes];
        $this->subscription = Subscription::where("sub_payment_ref",$ref)->first();
        $this->setStatus($data);
        // Now you can use $ref, $status, and $notes in your command logic
        // For example:
        // SomeService::processCallback($ref, $status, $notes);

        $this->info("Callback triggered with ref: $ref, status: $status, notes: $notes");
    }
}
