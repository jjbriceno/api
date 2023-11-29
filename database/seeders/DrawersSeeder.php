<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drawers;
use Illuminate\Support\Facades\DB;

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
                    ['name' => 'G' . $count],
                );
                $count++;
            }
        });
    }
}
