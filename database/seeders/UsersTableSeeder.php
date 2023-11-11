<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $adminUser = User::create(
                [
                    'name' => "José Briceño",
                    'email' => "bricenoj9@gmail.com",
                    'email_verified_at' => now(),
                    'password' => Hash::make("password"), // password
                    'remember_token' => Str::random(10),
                ]
            );

            if ($adminUser) {
                $adminUser->assignRole("Admin");
            }
        });
    }
}
