<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('times')->insert([
            [
                'name' => 'PM',
                'check_in' => '06:45',
                'check_out' => '14:30',
                'is_breakable' => false,
                'is_leave' => null,
                'is_rest' => null,
            ],
            [
                'name' => 'PS',
                'check_in' => '14:30',
                'check_out' => '22:00',
                'is_breakable' => false,
                'is_leave' => null,
                'is_rest' => null,
            ],
            [
                'name' => 'JN',
                'check_in' => '08:30',
                'check_out' => '16:00',
                'is_breakable' => false,
                'is_leave' => null,
                'is_rest' => null,
            ],
            [
                'name' => 'VV',
                'check_in' => '08:30',
                'check_out' => '16:00',
                'is_breakable' => true,
                'is_leave' => null,
                'is_rest' => null,
            ],
            [
                'name' => 'CONGÉ',
                'check_in' => null,
                'check_out' => null,
                'is_breakable' => null,
                'is_leave' => true,
                'is_rest' => null,
            ],
            [
                'name' => 'RH',
                'check_in' => null,
                'check_out' => null,
                'is_breakable' => null,
                'is_leave' => null,
                'is_rest' => true,
            ],
        ]);
    }
}
