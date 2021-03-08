<?php

namespace Tests\Unit;
use App\User;
use Spatie\Permission\Models\Role;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;
use Illuminate\Foundation\Testing\WithoutMiddleware;

use Tests\TestCase;

class JobTest extends TestCase
{
    use WithoutMiddleware;

    // public function test_can_create_job() {

    //     $user = User::findOrFail(1);

    //     $priority = "high";
    //     $command = "2 + 2";
        
    //     $response = $this->actingAs($user, 'api')->json('post', 'api/jobs/new', [
    //         'priority' => $priority,
    //         'command' => $command
    //     ]);

    //     $response->assertStatus(201);
            

        
    // }

}
