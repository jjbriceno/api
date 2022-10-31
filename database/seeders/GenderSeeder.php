<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $a = [
            "Aguinaldo",
            "Anónimo",
            "Bajo - Voces Oscuras",
            "Bolero",
            "Brasilero",
            "Calypso - Voces Oscuras",
            "Cambome",
            "Cancion de cuna",
            "Canticos de navidad",
            "Canto popular Cataleña - Voces Blancas",
            "Coro e instrumentos",
            "Coro Mixto",
            "Folklore Peru - SCTB",
            "Gregoriano movimiento",
            "Habanera Popular",
            "Himno Festividades",
            "Fragmento",
            "Gregoriano",
            "Himno",
            "Madrigal",
            "Madrigal - Piano",
            "Merengue Barines",
            "Melodia Indigena",
            "Merengue Popular",
            "Merengue",
            "Mixto",
            "Mixto - Voces Oscuras",
            "Mixto Joropo",
            "Mixto Voces Blancas",
            "Mixto y orquesta",
            "Movimiento",
            "Movimiento - Voces Blancas",
            "Musica popular",
            "Navidad",
            "Navideño",
            "Piano",
            "Popular",
            "Popular - SSA - Madrigal Venezolana",
            "Popular - Voces oscuras",
            "Popular Brasileña - SSA",
            "Popular frances",
            "Popular Argentino",
            "Popular Japonesa",
            "Popular venezolana",
            "Popular Voces Oscuras",
            "SA - popular",
            "SA+clarinete - Folklorica Australiana",
            "Sacro",
            "Sacro - Mixto",
            "Sacro - Mixto (solo)",
            "Sacro - VB",
            "Sacro - Voces Oscuras",
            "Sacro (solo)",
            "SAT - Aire de joropo",
            "SCB",
            "SCT",
            "SCTB",
            "SCTB - Aguinaldo",
            "SCTB - Bolero",
            "SCTB - Cancion Alemana",
            "SCTB - Danza",
            "SCTB - Gregoriano",
            "SCTB - Habanera",
            "SCTB - Himno",
            "SCTB - Huayna",
            "SCTB - Madrigal",
            "SCTB - Madrigal italiano",
            "SCTB - Merengue",
            "SCTB - Movimiento",
            "SCTB - Navidad",
            "SCTB - Oratorio",
            "SCTB - Popular",
            "SCTB - Popular ecuador",
            "SCTB - Sacro",
            "SCTB - Vals",
            "SCTB- Madrigal",
            "SCTB -Merengue",
            "SCTB- Vals Lento",
            "SSA - A Capella",
            "SSA - Danza Tradicional Argentina",
            "SSA - Madrigal",
            "SSA - Madrigal Venezolana",
            "SSA - popular",
            "SSA - popular Española",
            "SSA - Vals",
            "SSAA - Popular Cataluña",
            "Tenor",
            "Tono llanero - Mixto",
            "Tradicional",
            "TTBB",
            "Uruguayo",
            "Vals",
            "VB - SCTB",
            "VB - VO",
            "Villancico Cataluño",
            "Voces Blancas",
            "Voces Blancas - Aguinaldo",
            "Voces blancas - Cancion de cuna",
            "Voces Blancas - Cancion infantil",
            "Voces Blancas - Criolla",
            "Voces Blancas - Cumbia",
            "Voces Blancas - Folklore Colombiano",
            "Voces Blancas - Madrigal",
            "Voces Blancas - Movimiento",
            "Voces Blancas - Navidad",
            "Voces Blancas - Polpular - SSA",
            "Voces Blancas - Popular",
            "Voces Blancas - Popular - Canto Común",
            "Voces Blancas - Popular Brasileña",
            "Voces Blancas - Popular Ruso",
            "Voces Blancas - Popular(folklore)",
            "Voces Blancas Malagueña",
            "Voces Oscuras",
            "Walt Disney Dumbo",




        ];
        DB::transaction(function () use ($a) {
            foreach ($a  as $SuperA) {
                Gender::updateOrCreate(['name' => $SuperA]);
            }
        });
    }
}
