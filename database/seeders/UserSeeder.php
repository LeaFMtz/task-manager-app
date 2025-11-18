<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password')
        ]);
        
        $admin->assignRole('admin');

        $editor = User::create([
            'name' => 'Editor User',
            'email' => 'editor@test.com',
            'password' => Hash::make('password')
        ]);
        
        $editor->assignRole('editor');

        $viewer = User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@test.com',
            'password' => Hash::make('password')
        ]);
        
        $viewer->assignRole('viewer');
    }
}