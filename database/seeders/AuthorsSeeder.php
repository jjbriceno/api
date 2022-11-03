<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class AuthorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filename = base_path("database/seeders/data/Athors.csv");

        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $csvFile = fopen($filename, "r");

        DB::transaction(function () use ($csvFile) {

            while (($data = fgetcsv($csvFile, 0, ",")) !== FALSE)
                Author::updateOrCreate(['full_name' => $data[0]]);
        });
    }
}
