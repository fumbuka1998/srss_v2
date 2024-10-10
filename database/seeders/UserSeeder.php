<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username'=>'admin',
            'uuid'=> Uuid::uuid4()->toString(),
            'password'=>bcrypt('123456'),
            'email'=>'admin@gmail.com'
        ]);
    }
}
