<?php

namespace Database\Seeders;

use App\Models\Religion;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Religion::create([
            'uuid'=> Uuid::uuid4()->toString(),
            'name'=>'Muslim',
            'created_by'=>1
        ]);
        Religion::create([
            'uuid'=> Uuid::uuid4()->toString(),
            'name'=>'Christian',
            'created_by'=>1
        ]);
    }
}
