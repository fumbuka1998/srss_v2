<?php

namespace App\Exports;

use App\Models\CharacterAssessment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CharacterAssessmentValidationError implements ShouldAutoSize,FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $character_assessment_error;


    public function __construct($character_assessment_error){

        $this->character_assessment_error = $character_assessment_error;

    }

    public function view(): View
    {
        $assessments = CharacterAssessment::all();
        $data = ['validation_errors' => $this->character_assessment_error, 'assessments'=> $assessments];
        return  view('results.reports.generated.character_assessments.valiadation_errors',$data);

    }
}
