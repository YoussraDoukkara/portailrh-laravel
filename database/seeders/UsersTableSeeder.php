<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'YOUSSRA',
                'last_name' => 'DOUKKARA',
                'email' => 'YOUSSRA.DOUKKARA@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
        ]);

        $firstNames = ['AMAL', 'JAMILA', 'SAMIRA', 'RAJAE', 'FATIMA', 'LATIFA', 'NISRINE', 'ZINEB', 'YASMINE', 'BOUTAINA', 'ASMAE', 'SOUAD', 'SANA', 'IMA', 'DOUNIA', 'ANAS', 'YOUNES', 'HAMZA', 'MOUNIR', 'TAHA', 'BILAL', 'HASSAN', 'MOHAMMED', 'ABDELKARIM', 'ABDESSAMAD', 'SAID', 'HICHAM', 'KARIM', 'OMAR', 'AYOUB'];
        $lastNames = ['EL HAJJAMI', 'EL MOUTAWAKIL', 'EL HANAFI', 'BOUMEDIENE', 'OUDGHIRI', 'AMAROUCH', 'AHMIDOUCH', 'BOUNAJMA', 'CHAHBOUN', 'BELHAJ', 'EL BAKKALI', 'EL HASSANI', 'LAKHAL', 'BAKKALI', 'BOURAS', 'ESSAADOUNI', 'LAKHSSASSI', 'BENCHIKH', 'EL BACHIRI', 'AIT BAHA', 'BOUKILI', 'EL MOUDEN', 'ZERROUKI', 'KHADIR', 'AJDOR', 'EL JAAFARI', 'BAJEDDOUB', 'CHAHID', 'CHAOUKI', 'HAMRANI'];

        for ($i = 0; $i < 50; $i++) {
            $firstName = $firstNames[rand(0, count($firstNames) - 1)];
            $lastName = $lastNames[rand(0, count($lastNames) - 1)];

            $email = str_replace(' ', '.', $firstName . '.' . $lastName) . '@example.com';

            // check if email already exists in database
            $existingUser = DB::table('users')->where('email', $email)->first();

            if (!$existingUser) {
                DB::table('users')->insert([
                    [
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'email' => $email,
                        'password' => Hash::make('password'),
                    ],
                ]);
            }
        }
    }
}
