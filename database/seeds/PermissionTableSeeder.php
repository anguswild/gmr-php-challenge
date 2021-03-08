<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                //Here we create all the permission for the app
                $permissions_admin = [
                    'new-job',
                    'delete-job',
                    'get-job',
                    'check-job',
                    'new-user',
                    'get-user',
                    'update-user',
                    'delete-user',
                 ];
        
                 foreach ($permissions_admin as $permission) {
                      Permission::create(['name' => $permission]);
                 }
                 
                 $role = Role::create(['name' => 'Admin']);
                 $role->syncPermissions($permissions_admin);
        
        
                 $permissions_client = [
                    'new-job',
                    'delete-job',
                    'get-job',
                 ];
        
                 $role = Role::create(['name' => 'Client']);
                 $role->syncPermissions($permissions_client);
        
        
                 $permissions_processor = [
                    'get-job',
                 ];
        
                 $role = Role::create(['name' => 'Job Processor']);
                 $role->syncPermissions($permissions_processor);
    }
}
