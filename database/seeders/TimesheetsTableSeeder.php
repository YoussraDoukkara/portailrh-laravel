<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Time;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TimesheetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = Employee::all();

        for ($day = 1; $day <= 7; $day++) {
            for ($week = date("W") - 3; $week <= date("W") + 3; $week++) {
                foreach ($employees as $employee) {
                    $time = Time::inRandomOrder()->first();

                    DB::table('timesheets')->insert([
                        [
                            'employee_id' => $employee->id,
                            'time_id' => $time->id,
                            'week' => $week,
                            'day' => $day,
                        ],
                    ]);
                }
            }
        }
    }
}
