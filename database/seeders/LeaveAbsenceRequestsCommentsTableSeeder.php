<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LeaveAbsenceRequest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class LeaveAbsenceRequestsCommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $leaveAbsenceRequests = LeaveAbsenceRequest::all();

        foreach ($leaveAbsenceRequests as $leaveAbsenceRequest) {
            for ($i = 0; $i < rand(1, 30); $i++) {
                $employee = Employee::where('user_id', 1)->first();

                $values = [
                    $leaveAbsenceRequest->employee_id,
                    $employee->id,
                ];

                DB::table('leave_absence_request_comments')->insert([
                    [
                        'employee_id' => $values[array_rand($values)],
                        'leave_absence_request_id' => $leaveAbsenceRequest->id,
                        'body' => $faker->paragraphs(1, true),
                    ],
                ]);
            }
        }
    }
}
