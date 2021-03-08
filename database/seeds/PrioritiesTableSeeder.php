<?php

use Illuminate\Database\Seeder;
use App\Priority;

class PrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priority = Priority::create([
            'name' => 'high'
        ]);
        $priority = Priority::create([
            'name' => 'low'
        ]);
    }
}