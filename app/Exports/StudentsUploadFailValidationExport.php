<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsUploadFailValidationExport implements ShouldAutoSize, FromView
{
    protected $students_upload_failed_validation;

    public function __construct(array $students_upload_failed_validation)
    {
        $this->students_upload_failed_validation = $students_upload_failed_validation;
    }

    public function view(): View
    {
        $data = ['validation_errors' => $this->students_upload_failed_validation];
        return view('student-management.excels.validation_errors_excel', $data);
    }
}

