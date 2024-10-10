<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

     protected $model = Student::class;

    public function definition()
    {
        $gender = ['male','female'];
        $nationality = ['Tanzania','Kenya','Uganda'];
        $religion_ids = [1,2];
        $classes = [1,2,3,4];
        $streams = [1,2];
        $admission_types = ['transfered','started','continuing'];
        return [
            'uuid'=>$this->faker->uuid(),
            'firstname'=>$this->faker->firstName(),
            'lastname'=>$this->faker->lastName(),
            'dob'=>$this->faker->date(),
            'class_id'=>$this->faker->randomElement($classes),
            'stream_id'=>$this->faker->randomElement($streams),
            'middlename'=>$this->faker->firstNameMale(),
            'gender'=>$this->faker->randomElement($gender),
            'nationality'=>$this->faker->randomElement($nationality),
            'religion_id'=>$this->faker->randomElement($religion_ids),
            'admission_type'=>$this->faker->randomElement($admission_types),
            'registration_date'=>$this->faker->date(),
            'created_by'=>1
        ];
    }
}
