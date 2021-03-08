<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $table = "GMR_jobs";

    protected $fillable = [
        'submitter_id',
        'processor_id',
        'priority',
        'status',
        'command',
        'result'
    ];
}
