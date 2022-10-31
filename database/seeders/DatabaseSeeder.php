<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(AuthorsSeeder::class);
        $this->call(GenderSeeder::class);
        $this->call(DrawersSeeder::class);
        $this->call(CabinetsSeeder::class);
        $this->call(MusicSheetSeeder::class);
    }
}
