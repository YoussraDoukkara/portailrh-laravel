<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('designations')->insert([
            [
                'name' => 'RESPONSABLE RH',
            ],
            [
                'name' => 'A. RH & DIRECTION',
            ],
            [
                'name' => 'CHEF DE CAISSES',
            ],
            [
                'name' => 'SAV',
            ],
            [
                'name' => 'CHARGE SI',
            ],
            [
                'name' => 'SERVICE TECHNIQUE',
            ],
            [
                'name' => 'RESPONSABLE RM',
            ],
            [
                'name' => 'R. DE SECURITE',
            ],
            [
                'name' => 'C. DE GESTION',
            ],
            [
                'name' => 'SUP TEX. & BAZ.',
            ],
            [
                'name' => 'MC TEXTILE BAZAR',
            ],
            [
                'name' => 'MC PGC',
            ],
            [
                'name' => 'SUP LIQ. & EPIC.',
            ],
            [
                'name' => 'RESPONSABLE PF',
            ],
            [
                'name' => 'SUP. APLS',
            ],
            [
                'name' => 'CM BOUL & PAT',
            ],
            [
                'name' => 'CM TRAITEUR',
            ],
            [
                'name' => 'SUP  VRAC & FLEG',
            ],
            [
                'name' => 'CM BOU & VOL',
            ],
        ]);
    }
}
