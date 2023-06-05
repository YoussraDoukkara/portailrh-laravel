<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(10)->create();

        $this->call(UsersTableSeeder::class);
        $this->call(DesignationsTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(TeamsTableSeeder::class);
        $this->call(EmployeesTableSeeder::class);
        $this->call(TimesTableSeeder::class);
        $this->call(TimesheetsTableSeeder::class);
        $this->call(LeaveAbsenceRequestsTableSeeder::class);
        $this->call(LeaveAbsenceRequestsCommentsTableSeeder::class);
        $this->call(AttendancesTableSeeder::class);
        $this->call(DocumentCategoriesTableSeeder::class);
        $this->call(NotesTableSeeder::class);
        $this->call(PostsTableSeeder::class);
    }
}
