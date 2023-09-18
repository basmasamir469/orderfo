<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RoleSeeder::class);
        $admin=User::create([
            'fname'=>'admin',
            'lname'=>'admin',
            'phone'=>'01100122738',
            'email'=>'admin@admin.com',
            'password'=>Hash::make(123456),
            'is_active_email'=>1,
            'is_active_phone'=>1
        ]);
        $role=Role::where(['name'=>'admin','guard_name'=>'api'])->first();
        $admin->assignRole($role);
        $this->call(CategorySeeder::class);
    }
}
