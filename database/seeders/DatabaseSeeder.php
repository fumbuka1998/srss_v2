<?php

namespace Database\Seeders;

use App\Models\Student;
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
        $this->call(
            [
            OrganizationSeeder::class,
            ReligionSeeder::class,
            UserSeeder::class
             ]
             );

        Student::factory()->count(100)->create();

    }
}
