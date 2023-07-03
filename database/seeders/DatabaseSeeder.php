<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            "name" => "Admin",
            "email" => env('ADMIN_EMAIL'),
            "password" => bcrypt(env('ADMIN_PASSWORD')),
        ]);
    }
}
