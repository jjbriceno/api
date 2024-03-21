<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users_adim = [
            [
                'name' => "José Briceño",
                'email' => "bricenoj9@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make("password"), // password
                'remember_token' => Str::random(10),
            ],
            [
                'name' => "Francisco Peña",
                'email' => "javierrupe19@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make("password"), // password
                'remember_token' => Str::random(10),
            ],
        ];
        DB::transaction(function () use ($users_adim) {
            foreach ($users_adim as $user) {
                $user_admin = User::create($user);
                $user_admin->profile()->create([
                    'first_name' => explode(" ", $user['name'])[0],
                    'last_name'  => explode(" ", $user['name'])[1],
                    'address'    => fake()->address(),
                    'phone'      => fake()->phoneNumber(),
                ]);

                if ($user_admin) {
                    $user_admin->assignRole("admin");
                }
            }
        });

        $faker = Faker::create();

        // Add 100 fake users
        DB::transaction(function () use ($faker) {
            $users = User::factory(100)->create();
            foreach ($users as $user) {
                $user->profile()->create([
                    'first_name' => $user->name,
                    'last_name'  => $faker->lastName,
                    'address'    => $faker->address,
                    'phone'      => $faker->phoneNumber,
                ]);
                $user->assignRole("user");
            }
        });
    }
}
