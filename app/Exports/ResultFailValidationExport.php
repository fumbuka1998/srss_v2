<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ResultFailValidationExport implements ShouldAutoSize,FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $results_failed_validation;


    public function __construct($results_failed_validation){
        $this->results_failed_validation = $results_failed_validation;


    }

    public function view(): View
    {
        $data = ['validation_errors' => $this->results_failed_validation];
        return  view('results.marking.validation_errors_excel',$data);

    }
}
