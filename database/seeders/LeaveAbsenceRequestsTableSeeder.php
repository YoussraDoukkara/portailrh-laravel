<?php

namespace Database\Seeders;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class LeaveAbsenceRequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $reasons = ['illness', 'mission', 'other'];
        $startsAt = [
            Carbon::today(),
            Carbon::tomorrow(),
        ];
        $endsAt = [
            Carbon::now()->addDays(3)->toDateString(),
            Carbon::now()->addDays(4)->toDateString(),
        ];

        $employees = Employee::inRandomOrder()->take(10)->get();

        foreach ($employees as $employee) {
            DB::table('leave_absence_requests')->insert([
                [
                    'employee_id' => $employee->id,
                    'body' => $faker->paragraphs(1, true),
                    'reason' => $reasons[array_rand($reasons)],
                    'starts_at' =>  $startsAt[array_rand($startsAt)],
                    'ends_at' =>  $endsAt[array_rand($endsAt)],
                ],
            ]);
        }
    }
}
