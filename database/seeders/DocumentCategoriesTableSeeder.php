<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DocumentCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('document_categories')->insert([
            [
                'name' => 'Formulaires',
            ],
            [
                'name' => 'Instructions',
            ],
        ]);
    }
}
