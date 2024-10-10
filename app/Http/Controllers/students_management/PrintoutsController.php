<?php

namespace App\Http\Controllers\students_management;

use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Models\Religion;
use App\Models\ReligionSect;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentClub;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PrintoutsController extends Controller
{
    //

    public function pdf(Request $request){

        // return $request;


        $students = Student::leftjoin('student_clubs','students.id','=','student_clubs.student_id')
                    ->select('students.*','student_clubs.club_id','clubs.name as club_name')
                    ->leftjoin('clubs', 'clubs.id', '=','student_clubs.club_id')
                    ->orderBy('id','desc');

        if (is_numeric($request->class_id)) {
            $students = $students->where('class_id',$request->class_id);
            $data['class'] = SchoolClass::find($request->class_id)->name;
         }

        //  if (is_numeric($request->stream_id)) {
        //      $students = $students->where('stream_id',$request->stream_id)
        //      ->where('class_id',$request->class_id);
        //      $data['stream'] = SchoolClass::find($request->stream_id)->name;
        //   }

        if (is_numeric($request->stream_id)) {
            $stream = SchoolClass::find($request->stream_id);
        
            if ($stream) {
                $students = $students->where('stream_id', $request->stream_id)
                    ->where('class_id', $request->class_id);
        
                $data['stream'] = $stream->name;
            } else {
                $data['stream'] = 'Stream Not Found';
            }
        }
        
          if (is_numeric($request->club_id)) {

            $students = $students->where('club_id',$request->club_id);
            $data['club'] = StudentClub::where('club_id',$request->club_id)->first()->name;
         }

         if (is_numeric($request->house_id)) {
            $students = $students->where('house_id',$request->house_id);
            $data['house'] = Religion::find($request->house_id)->name;
         }

         if (is_numeric($request->religion_id)) {
            $students = $students->where('religion_id',$request->religion_id);
            $data['religion'] = Religion::find($request->religion_id)->name;
         }

         if (is_numeric($request->sect)) {
            $students = $students->where('religion_sect',$request->sect);
            $data['sect'] = ReligionSect::find($request->sect)->name;
         }

        //  $data['box_office'] =

         $data['students'] = $students->get();

        if ($request->file_type == 'pdf') {
            // return view('registration::printouts.students_pdf')->with($data);
            // return view('student-management.on-going.printouts.pdf',$data);



            $pdf = PDF::loadView('student-management.on-going.printouts.pdf', $data);

            // $pdf = PDF::loadView('registration::printouts.students_pdf', $data);
            return $pdf->stream('students.pdf');

        }
        if ($request->file_type == 'excel') {

            return Excel::download(new StudentsExport, 'students.xlsx');

        } 

    }











}
