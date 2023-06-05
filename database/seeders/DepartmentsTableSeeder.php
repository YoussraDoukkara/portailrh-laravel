<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
                'name' => 'P.G.C',
            ],
            [
                'name' => 'BAZAAR',
            ],
            [
                'name' => 'CAISSES',
            ],
            [
                'name' => 'RH',
            ],
            [
                'name' => 'PRODUITS FRAIS',
            ],
            [
                'name' => 'EM',
            ],
            [
                'name' => 'SECURITE',
            ],
            [
                'name' => 'COMTABILITÉ',
            ],
            [
                'name' => 'TEXTILE',
            ],
            [
                'name' => 'TECHNIQUE',
            ],
            [
                'name' => 'INFORMATIQUE',
            ],
            [
                'name' => 'RECEPTION MARCHENDISE',
            ],
        ]);
    }
}
