<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drawers;
use DB;

class DrawersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $count = 1;
            while ($count < 5) {
                Drawers::updateOrCreate(
                    ['name' => 'Estante' . $count],
                    ['cabinets_cuantity' => '4']
                );
                $count++;
            }
        });
    }
}
