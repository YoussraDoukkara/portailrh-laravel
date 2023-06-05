<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Time;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AttendancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            foreach ($employee->timesheets as $timesheet) {
                $time = $timesheet->time;

                if (!$time->is_leave  && !$time->is_rest) {
                    $checkIn = [$time->check_in, null];
                    $checkOut = [$time->check_out, null];
                    $breakIn = [$time->break_in, null];
                    $breakOut = [$time->break_out, null];

                    $firstDayOfYear = Carbon::now()->startOfYear();
                    $date = $firstDayOfYear->addWeeks($timesheet->week)->addDays($timesheet->day);

                    DB::table('attendances')->insert([
                        [
                            'employee_id' => $employee->id,
                            'check_in' => $checkIn[array_rand($checkIn)],
                            'check_out' => $checkOut[array_rand($checkOut)],
                            'break_in' => $breakIn[array_rand($breakIn)],
                            'break_out' => $breakOut[array_rand($breakOut)],
                            'created_at' => $date->format('Y-m-d'),
                        ],
                    ]);
                }
            }
        }
    }
}
