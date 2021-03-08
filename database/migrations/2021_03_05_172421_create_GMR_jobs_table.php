<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGMRJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('GMR_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submitter_id');
            $table->foreignId('processor_id')->nullable();
            $table->char('priority', 50);
            $table->char('status', 50);
            $table->text('command')->nullable();
            $table->text('result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('GMR_jobs');
    }
}
