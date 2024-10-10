@extends('layout.index')
@section('top-bread')
@include('student-management.profile_breadcrumb')
@endsection
@section('body')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css ">
<style>

.image-container {
    /* position: relative; */
    width: 200px; /* Adjust the size as needed */
}

.edit-icon {
    position: absolute;
    font-size: 22px;
    color: #007bff;
    cursor: pointer;
}

.user-profile-img img {
border-radius: 0 !important;

}
 .th-hover:hover{
    cursor: pointer;
}

.col-border{
    border-right: 1px solid #17a2b8;
    top: 0;
}

.col-border-top{
    border-top: 1px solid #17a2b8;
}

</style>

<div class="card">

    <div class="card-body">


        <div class="row">


          @include('student-management.profile_part')

            <div class="col-md-9">
                @include('student-management.the_nav')

                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sevillana&display=swap');

                .data-head {
                    padding: 0.5rem 1.25rem;
                    margin-bottom: 0.25rem;
                    background-color: #ebeef2;
                    border-radius: 4px;
                }

                .col-display{
                    flex-direction: column;
                }
                .th-color{
                   background-color: #069613;
                   color: #ffff;
                }
                .s1{
                    color: #069613;
                }

                /* my css */

                /* .main{
                    font-family: "Poppins", sans-serif;
                    background-color: rgb(22,231,221);
                    background-color: linear-gradient(270deg, rgba(22,231,221) 0%,
                                        rgba(33, 181, 237,1)35%, rgba(0,255,239,1)100%);

                } */
                .timeline{
                    border-left:1px dashed hsl(193deg, 84%, 52%);
                    margin-left:20px;
                    margin-bottom:30px;
                }

                .timeline .timeline-box{
                    padding-left: 40px;
                    margin-bottom:30px;
                    position:relative;
                }

                .timeline-icon{
                    left:-18px;
                    top:0;
                    background-color:#20bfeb;
                    position:absolute;
                    width:35px;
                    z-index:1;
                    text-align: center;
                    line-height:35px;
                }
                .timeline-icon i {
                    color: #fff;
                }

                .timeline .timeline-icon:after{
                    content:"";
                    position: absolute;
                    left: top;
                    top: 0;
                    background-color: #16e7dd;
                    opacity: 0.5;
                    z-index: -1;
                    right: 0;
                    bottom: 0;
                    margin: -4px;
                }








                </style>

               <div class="main">
                <div class="row mt-4 col-display">
                    <div class="col-md-12">
                        <table class="table responsive compact" id="student_result_table" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="th-color">Report Name</th>
                                    <th class="th-color">Academic Year</th>
                                    <th class="th-color">Terms</th>
                                    <th class="th-color">Divions</th>
                                    <th class="th-color">Points</th>
                                    <th class="th-color">Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>

                </div>


                  {{-- creating the timeline view for reports --}}
                  {{-- <section class="container">
                    <div class="row shadow my-4 p-2 bg-white rounded-lg">
                        <div class="col-md-6">
                            <div class="py-2">
                                <h5>All Published Results</h5>
                            </div>
                            <div class="timeline">

                                @if($report_infos)
                                @foreach($report_infos as $info)

                                <div class="timeline-box">
                                    <div class="timeline-icon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <h4>{{ $info->name }}</h4>
                                        <hr class="bg-info " >
                                        <div class="table_to_results"> --}}


                                            {{-- <table class="table1" style="width:100%; margin-top: 10px" border="1">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" style="text-align: left;font-size: 11px">SUBJECTS</th>
                                                        <th colspan="{{ $span }}"> DAILY PROGRESS </th>
                                                        @foreach ($exams  as $key => $exam )
                                                        @if (!$exam->is_dp)
                                                        <th rowspan="2" >{{ $exam->code }} / {{ $exam->total_marks }}</th>
                                                        @endif
                                                        @endforeach
                                                        <th rowspan="2" style="text-align:center" data-subject_id="" class="text-center">AVG %</th>
                                                        <th rowspan="2" style="text-align:center" data-subject_id=""  class="text-center">GRD</th>
                                                        <th rowspan="2" style="text-align:center" data-subject_id=""  class="text-center">PNTS</th>
                                                        <th  rowspan="2" colspan="3" style="text-align: left;font-size: 11px" class="text-center">REMARKS</th>
                                                    </tr>

                                                    <tr>
                                                        @foreach ($exams  as $key => $exam )
                                                        @if ($exam->is_dp)
                                                        <th> {{ $exam->code }} / {{ $exam->total_marks }}</th>
                                                        @endif
                                                        @endforeach
                                                    </tr>
                                                </thead>

                                            <tbody> --}}


                                            {{-- @foreach ($rst[$student]['subjects'] as $subject)
                                            @php
                                                $subject_id = $subject->sbjct_id;
                                                $score = '-';
                                                $check_module = $rst[$student_id]['metadata']->results;
                                            @endphp

                                            <tr>
                                                @if (isset($check_module->$subject_id))
                                                <td>{{ $subject->subject_name }}</td>
                                                @php
                                                    $points = isset($rst[$student_id]['metadata']->results->$subject_id) ? $rst[$student_id]['metadata']->results->$subject_id->POINT : '-';
                                                    $avg = isset($rst[$student_id]['metadata']->results->$subject_id) ? $rst[$student_id]['metadata']->results->$subject_id->AVG : '-';
                                                    $grade = isset($rst[$student_id]['metadata']->results->$subject_id) ? $rst[$student_id]['metadata']->results->$subject_id->GRADE : '-';
                                                    $remarks = isset($rst[$student_id]['metadata']->results->$subject_id) ? $rst[$student_id]['metadata']->results->$subject_id->REMARKS : '-';
                                                @endphp
                                                    @foreach ($exams as $exam)
                                                        @php
                                                            $exam_id = $exam->id;
                                                            $module_subject = $check_module->$subject_id;
                                                            $score = isset($module_subject->$exam_id) ? $module_subject->$exam_id : '-';
                                                        @endphp
                                                <td style="text-align:center">{{ $score }}</td>
                                                    @endforeach
                                                    @else

                                                @endif
                                                <td style="text-align:center">{{ $avg }}</td>
                                                <td style="text-align:center">{{ $grade }}</td>
                                                <td style="text-align:center">{{ $points }}</td>
                                                <td colspan="3">{{ $remarks }}</td>
                                            </tr>

                                            @endforeach
                                                <tr>
                                                    <td >Total/avg/Grade</td>
                                                    <td colspan="{{ $span }}"></td>
                                                    <td style="text-align: center">{{ $rst[$student_id]['metadata']->AVG}}</td>
                                                    <td style="text-align: center">{{ $rst[$student_id]['metadata']->GRADE}}</td>
                                                    <td></td>
                                                    <td rowspan="4" colspan="3"></td>
                                                </tr>

                                                <tr>
                                                    <td>DIVISION</td>
                                                    <td style="text-align: center" colspan="{{ $span }}">{{ $rst[$student_id]['results']->division}}</td>
                                                    <td style="text-align: center" colspan="{{ $span }}">POINTS</td>
                                                    <td style="text-align: center">  {{ $rst[$student_id]['results']->points}}</td>
                                                </tr>

                                                <tr>
                                                    <td>Attendance (x/y)</td>
                                                    <td colspan="{{ $span }}"></td>
                                                    <td colspan="{{ $span +1 }}"></td>
                                                </tr>

                                                <tr>
                                                    <td>No Of Days Late (x/y)</td>
                                                    <td colspan="{{ $span }}"></td>
                                                    <td colspan="{{ $span +1 }}"></td>
                                                </tr> --}}

                                                {{-- <tr>
                                                    <td>Class Teacher's Comment</td>
                                                    <td colspan="8">{{ $comment }}</td>
                                                </tr>

                                                <tr>
                                                    <td rowspan="2">Class Teacher's Name</td>
                                                    <td rowspan="2" colspan="3"> {{ $class_teacher->full_name }} </td>
                                                    <td>Signature</td>
                                                    <td colspan="4"></td>
                                                </tr>

                                                <tr>
                                                    <td>Date</td>
                                                    <td colspan="4"></td>
                                                </tr>

                                                <tr>
                                                    <td rowspan="2">Parents Name</td>
                                                    <td rowspan="2" colspan="3"></td>
                                                    <td>Signature</td>
                                                    <td colspan="4"></td>
                                                </tr>

                                                <tr>
                                                    <td>Date</td>
                                                    <td colspan="4"></td>
                                                </tr> --}}

{{--
                                                </tbody>

                                            </table> --}}











                                        {{-- table ends here  --}}



                                        {{-- </div>
                                    </div>
                                </div>


                                @endforeach


                                @endif


                            </div>
                        </div>
                    </div>

                </section> --}}






               </div>
            </div>
        </div>
    </div>
</div>





@section('scripts')
<script>

let uuid = @json($uuid);


/* datatable */
let student_result_url_init = '{{ route('students.profile.results.datatable',[':id']) }}';
let cnct_url = student_result_url_init.replace(':id', uuid);
let datatable = $('#student_result_table').DataTable({
        processing: true,
        serverSide: true,
        ajax:cnct_url,
        columns:[
      {data: 'report_name', name:'report_name'},
    //   {data: 'exam_type_combination', name:'exam_type_combination'},
      {data: 'academic_year', name:'academic_year'},
      {data: 'terms', name:'terms'},
      {data: 'divisions', name:'divisions'},
      {data: 'points', name:'points'},
      {data:'action', name:'action', orderable:false, searchable:false}
        ],
        "columnDefs": [
        // { className: " text-right font-weight-bold", "targets": [ 1 ] },
        // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
        // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
      ],

      drawCallback:function(){

$('.delete').click(function(){
    let url = "{{ route("academic.class.teachers.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});



}

});







</script>

@endsection



@endsection
