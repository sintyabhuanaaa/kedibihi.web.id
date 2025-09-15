<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Admin Default',
            'email' => 'kedibihi@gmail.com',
            'password' => Hash::make('kedibihi05072025'),
        ]);
    }
}
