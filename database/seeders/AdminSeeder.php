<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'uuid' => (string) Str::uuid(),
            'surname' => 'Mr. ',
            'first_name' => 'John',
            'last_name' => 'Smith',
            'username' => 'john',
            'email' => 'john@gmail.com',
            'password' => Hash::make('HarpExr1153$'),
            'status' => 1
        ]);
    }
}
