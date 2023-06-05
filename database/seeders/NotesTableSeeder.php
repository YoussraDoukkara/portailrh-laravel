<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LeaveAbsenceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class NotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $users = User::all();

        foreach ($users as $user) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                DB::table('notes')->insert([
                    [
                        'user_id' => $user->id,
                        'body' => $faker->paragraphs(1, true),
                    ],
                ]);
            }
        }
    }
}
