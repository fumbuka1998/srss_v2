<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ResultsUploadTemplate implements FromView,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public $students;


    public function __construct($students){

        $this->students = $students;

    }
    public function view(): View
    {
        return view('results.template', [
            
            'students' => $this->students,
        ]

    );

    }
}
