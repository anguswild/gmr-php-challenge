<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Job;



class newJob implements ShouldQueue
{
    private $job_id;
    private $command;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($job_id, $command)
    {
        $this->job_id = $job_id;
        $this->command = $command;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $job = Job::findOrFail($this->job_id);

        $job->command = $this->command;

        $job->status = "Accepted";

        $job->save();
        Log::info('Accepted job ' . $job->id);
    }
}
