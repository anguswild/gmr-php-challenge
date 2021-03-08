<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;




class UserTest extends TestCase
{
    public function testLogin()
    {
        $username = "admin";
        $password = "8dB8ZFfWcmB3ZkqL";
    
        $response = $this->json('POST', 'api/login', [
            'username' => $username,
            'password' => $password
        ]);
    
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
            ]);
    }

    public function test_user_can_get_details()
    {   
        $user = User::findOrFail(1);
        $response = $this->actingAs($user, 'api')->json('GET', 'api/users/details');
    
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
            ]);
    }

    public function test_user_can_get_user_list()
    {   
        $user = User::findOrFail(1);
        $response = $this->actingAs($user, 'api')->json('GET', 'api/users');
    
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
            ]);
    }
    public function test_user_can_get_one_user()
    {   
        $user = User::findOrFail(1);
        $response = $this->actingAs($user, 'api')->json('GET', 'api/users/1');
    
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
            ]);
    }
}
