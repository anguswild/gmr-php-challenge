<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Job;

class getJob implements ShouldQueue
{
    private $job_id;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job_id)
    {
        $this->job_id = $job_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $job = Job::findOrFail($this->job_id);

        $command = $job->command;
        $result = eval("return $command;");

        $job->result = $result;
        $job->status = "Processed";
        $job->save();
        Log::info('Processed job ' . $job->id);
        
        
    }
}
