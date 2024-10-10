<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Organization::create([
            'name'=>'Shaaban Robert Secondary School',
            'address'=>'P.O BOX 777 Dar Es Salaam',
            'email'=>'shaabanrobert@gmail.com',
            'profile'=>'profile.png'
        ]);
    }
}
