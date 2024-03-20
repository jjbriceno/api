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
        $users = [
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
        DB::transaction(function () use ($users) {
            foreach ($users as $user) {
                $user = User::create($user);
                $user->profile()->create([
                    'first_name' => explode(" ", $user['name'])[0],
                    'last_name'  => explode(" ", $user['name'])[1],
                    'address'    => fake()->address(),
                    'phone'      => fake()->phoneNumber(),
                ]);

                if ($user) {
                    $user->assignRole("admin");
                }
            }
        });

        $faker = Faker::create();

        for ($i = 0; $i < 100; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
            ]);
        }

        $users = DB::table('users')->pluck('id');

        foreach ($users as $userId) {
            DB::table('profiles')->insert([
                'user_id' => $userId,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'address' => $faker->address,
                'phone' => $faker->phoneNumber,
            ]);
        }
    }
}
