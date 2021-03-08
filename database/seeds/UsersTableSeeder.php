<?php

use Illuminate\Database\Seeder;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Arr;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_input = [
            'id'                 => 1,
            'name'               => 'Admin',
            'email'              => 'patricio.quezada05@gmail.com',
            'username'           => 'admin',
            'password'           => bcrypt('8dB8ZFfWcmB3ZkqL'),
        ];
    $user = User::create($user_input);
    $user->assignRole('Admin');

    factory(User::class)->times(20)->create()->each(function ($user) {
        $roles = ['Client', 'Job Processor'];
        $random_role = Arr::random($roles, 1);
        $user->assignRole($random_role); 
    });
        
    }
}
