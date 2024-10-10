<?php

namespace App\Exports;

use Illuminate\Contracts\View\View as ViewView;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CharacterAssessmentTemplateExport implements FromView,ShouldAutoSize

{
    /**
    * @return \Illuminate\Support\Collection
    */


    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): ViewView
    {
        return view('results.reports.generated.character_assessments.template', $this->data

    );

    }
}
