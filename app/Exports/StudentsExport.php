<?php

namespace App\Exports;

use App\Models\Student;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExport implements FromView,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        return view('student-management.on-going.printouts.export', [
            'students' => Student::all(),
            //  'school_info' => AccountSchoolDetail::first(),
            //  'address' =>  Contact::where('contactable_type',AccountSchoolDetail::class)
            //  ->where('contact_type_id',3)->first(),

            //  'phone' => Contact::where('contactable_type',AccountSchoolDetail::class)
            //  ->where('contact_type_id',1)->first(),

            // 'email' => Contact::where('contactable_type',AccountSchoolDetail::class)
            // ->where('contact_type_id',2)->first(),

        ]

    );

    }
}
