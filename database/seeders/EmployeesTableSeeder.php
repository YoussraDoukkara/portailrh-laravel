<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Team;
use App\Models\User;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            if ($user->id == 1) {
                DB::table('employees')->insert([
                    [
                        'user_id' => $user->id,
                        'designation_id' => Designation::inRandomOrder()->first()->id,
                        'department_id' => Department::inRandomOrder()->first()->id,
                        'team_id' => Team::inRandomOrder()->first()->id,
                        'id_number' => strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1)) . substr(str_shuffle('0123456789'), 0, 6),
                        'registration_number' => substr(str_shuffle('0123456789'), 0, 8),
                        'payroll_number' =>  substr(str_shuffle('0123456789'), 0, 6) . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1)),
                        'is_department_head' => false,
                        'is_team_head' => false,
                    ],
                ]);

                continue;
            }

            DB::table('employees')->insert([
                [
                    'user_id' => $user->id,
                    'designation_id' => Designation::inRandomOrder()->first()->id,
                    'department_id' => Department::inRandomOrder()->first()->id,
                    'team_id' => Team::inRandomOrder()->first()->id,
                    'id_number' => strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1)) . substr(str_shuffle('0123456789'), 0, 6),
                    'registration_number' => substr(str_shuffle('0123456789'), 0, 8),
                    'payroll_number' =>  substr(str_shuffle('0123456789'), 0, 6) . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 1)),
                    'is_department_head' => in_array($user->id, [2, 6, 10, 14]),
                    'is_team_head' => in_array($user->id, [19, 22, 25, 28]),
                ],
            ]);
        }
    }
}
