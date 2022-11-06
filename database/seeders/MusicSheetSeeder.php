<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Gender;
use App\Models\Drawers;
use App\Models\Cabinets;
use App\Models\Locations;
use App\Models\MusicSheet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MusicSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filename = base_path("database/seeders/data/MusicSheets.csv");

        if (!file_exists($filename) || !is_readable($filename)) {
            return false;
        }

        $csvFile = fopen($filename, "r");

        DB::transaction(function () use ($csvFile) {
            while (($data = fgetcsv($csvFile, 0, ",")) !== false) {
                $title = trim($data[0], " ");
                $author_name = trim($data[1], " ");
                $cuantity = trim($data[2], " ");
                $gender_name = trim($data[3], " ");
                list($drawer_name, $cabinet_name) = explode('-', str_replace(' ', '', $data[4]));

                $drawer_id = Drawers::where('name', $drawer_name)->first()->id;
                $cabinet_id = Cabinets::where('name', $cabinet_name)->first()->id;
                $author_id = Author::where('full_name', $author_name)->first()->id;
                $genders_id = Gender::where('name', $gender_name)->first()->id;

                $lacation = Locations::create([
                    'cabinet_id'    => $cabinet_id,
                    'drawer_id'     => $cabinet_id
                ]);

                MusicSheet::create([
                    'author_id'     => $author_id,
                    'gender_id'     => $genders_id,
                    'location_id'  => $lacation->id,
                    'title'         => $title,
                    'cuantity'      => $cuantity,
                    'available'     => $cuantity
                ]);
            }
        });
    }
}
