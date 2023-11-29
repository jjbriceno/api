<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Cabinets;
use Illuminate\Support\Facades\DB;

class CabinetsSeeder extends Seeder
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
                Cabinets::updateOrCreate(
                    ['name' => 'Estante' . $count],
                    ['drawers_cuantity' => '4']
                );
                $count++;
            }
        });
    }
}
