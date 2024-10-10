
@extends('layout.index')


@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">GENERATED REPORTS INDRIVE</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('results.reports.generated.reports.index')}}"><i class="icon ion-ios-home-outline"></i> Generated Reports</a>
            <span class="breadcrumb-item active mr-3">Generated Reports Indrive</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<style>


.table-container {
overflow-x: scroll; /* Enable horizontal scroll */
position: relative;
}

.tabloid th {
background-color: #069613; /* Header background color */
color: #fff; /* Header text color */
}
/*
/* Freeze the first 3 columns */
.table-wrapper .frozen{
position: sticky;
left: 0;
z-index: 2;
/* background-color: #fff; */

}

.table-wrapper .frozen-td{
background-color: #fff;
}

/* Make the first three columns sticky */
/* end */
</style>

<div class="card mt-4">
    <div class="card-body">
    <div class="row clearfix">


        <div class="col-md-12">
            @include('results.reports.generated.indrive_nav')
        </div>


        <div class="col-md-12" style="margin-bottom: 1rem">
            <span style="float:right">
                @if (auth()->user()->hasRole('Teacher') && $generated_exam_report->escalation_level_id == 1)
                <button  title="Escalate Report" type="button"  style="color:white" data-uuid="{{ $generated_exam_report->uuid }}"  class="escalate_report btn btn-info btn-sm"> <i class="fa-solid fa-person-arrow-up-from-line"></i> Escalate to Academic Office </button>
                @endif

                @if (auth()->user()->hasRole('Academic') && $generated_exam_report->escalation_level_id == 2 )
                <button title="Escalate Report" type="button"  style="color:white" data-uuid="{{ $generated_exam_report->uuid }}"  class="escalate_report btn btn-info btn-sm"> <i class="fa-solid fa-person-arrow-up-from-line"></i> Approve & Escalate to HM </button>
                @endif

                <a href="javascript:void(0)" title="pdf" id="pdfButton"  style="color:white; background:#377493;"  class="btn btn-sm {{ $generated_exam_report->is_published ?? 'd-none'  }} "><i class="fa fa-print"></i> Print Pdf </a>
            </span>

    </div>



    <div class="col-md-12">
        <div class="table-container">
            <div class="table-wrapper">
        <form id="single_exam_type_no_subject">
            <input type="hidden" value="{{ encrypt($matokeo)  }}" name="our_token" id="our_token">


            <table class="table table-bordered tabloid" id="results" >
                <thead>
                    <tr>
                        @php
                            $colspan = 0;
                                foreach ($subjects as $key => $subject){
                                    $colspan +=1;
                                }

                        @endphp
                        @if ($generated_exam_report->is_published)
                        <th rowspan="3" class="frozen"> <label style="display: flex"> <input type="checkbox"  class="checkbox checkall">  &nbsp;<span>C/U</span>  </label> </th>
                        @endif
                        <th rowspan="3" class="frozen tabloid-th">SN</th>
                        <th rowspan="3" class="frozen tabloid-th" style="text-align: center; width:20rem">ADMISSION NUMBER</th>
                        <th style="min-width:25rem" class="text-center frozen" rowspan="3">FULL NAME</th>
                        <th style="text-align:center" class="text-center" colspan="{{ $colspan * 3}}">
                        SUBJECTS
                        </th>
                        <th class="tabloid-th" style="text-align:center" rowspan="3"> AVG </th>
                        <th class="tabloid-th" style="text-align:center" rowspan="3"> DIV </th>
                        <th class="tabloid-th" style="text-align:center" rowspan="3"> POINTS </th>
                        <th class="tabloid-th" style="text-align:center; min-width:10rem"  rowspan="3"> REMARKS </th>
                        <th class="tabloid-th" colspan="4"  style="text-align:center; min-width:20rem" rowspan="3"> C/T Comments </th>
                        @if (auth()->user()->hasRole('Head Master') || $trigger_hm > 0 )
                        <th class="tabloid-th" style="text-align:center; min-width:20rem" rowspan="3"> H/M comments </th>
                        @endif
                    </tr>
                    <tr>

                        @foreach ($subjects as $key => $subject)

                            <th style="text-align:center" colspan="3" data-subject_uuid="{{ $subject->uuid }}">{{ $subject->subject_name }} </th>
                            @php
                                $sbjct_columns[] = [
                                'name'=> $subject->subject_name,
                                'subject_id'=> $subject->sbjct_id,
                            ];
                            @endphp

                        @endforeach
                    </tr>

                    <tr>


                        @foreach ($sbjct_columns as $key => $column)
                            <th style="text-align:center" data-subject_id="{{ $column['subject_id'] }}" class="text-center">Marks/{{$examInfo->total_marks}}</th>
                            <th style="text-align:center" data-subject_id="{{ $column['subject_id'] }}" class="text-center">%</th>
                            <th style="text-align:center" data-subject_id="{{ $column['subject_id'] }}" class="text-center">Grade</th>
                            @endforeach

                        </tr>


                </thead>

                <tbody>

                    @foreach ($matokeo as $key=> $tokeo )

                            @php

                            $metadata = json_decode($tokeo->metadata);
                            $results = isset($metadata->results) ? $metadata->results : array() ;

                            @endphp

                    <tr>
                        @if ($generated_exam_report->is_published)
                        <td><input style="font-size: 20rem" type="checkbox" value="{{ $tokeo->student_id }}" class="checkbox other"></td>
                        @endif
                        <td>{{ ++$key }}</td>
                        <td> {{ $tokeo->student_id }} </td>
                        <td class="frozen frozen-td">{{ $tokeo->full_name }}</td>

                        @for ($i=0; $i<count($sbjct_columns);  $i++)
                            @php
                             $subject_id = $sbjct_columns[$i]['subject_id'];
                                $score = '-';
                                $percent = '-';
                                $grade = '-';

                                    if (isset($results->$subject_id)) {
                                      $to_score = $results->$subject_id;
                                      $score =  $to_score->score;
                                      $percent = $to_score->percent;
                                      $grade =  $to_score->grade;
                                }
                            @endphp
                            <td> {{ $score }} </td>
                            <td> {{ $percent }} </td>
                            <td> {{ $grade }} </td>
                            @endfor
                        <td> {{ $metadata->AVG }} </td>
                        <td> {{ $metadata->CODE }} </td>
                        <td> {{ $metadata->POINTS }} </td>
                        <td>{{ $metadata->REMARKS }}</td>


                        @if (auth()->user()->id == $tokeo->user_id && $generated_exam_report->escalation_level_id == 1 )
                        <td colspan="4">
                            <div>
                                @if ($tokeo->ct_predefined_comment_ids)
                               <span> {{ $tokeo->ct_comments }} <a href="javascript:void(0)" class="edit-ct"> <i class="fa fa-edit"></i> </a> </span>
                               <select data-type="CT"  data-uuid={{ $tokeo->uuid }} data-student_id = '{{ $tokeo->student_id }}' name="ct_comment[{{ $tokeo->student_id }}]" class="form-control toggle d-none comments ct_comment">
                                <option value=""> Select A Comment..... </option>
                                @foreach ($predefined_comments as $comment )
                                <option value="{{ $comment->id }}" {{  $tokeo->ct_predefined_comment_ids == $comment->id ? 'selected' : ''  }}  > {{ $comment->comment  }} </option>
                                @endforeach
                              </select>
                                @else
                                <select data-uuid={{ $tokeo->uuid }} data-student_id = '{{ $tokeo->student_id }}' data-type="CT" name="ct_comment[{{ $tokeo->student_id }}]" class="form-control ct_comment comments">
                                    <option value=""> Select A Comment..... </option>
                                    @foreach ($predefined_comments as $comment )
                                    <option value="{{ $comment->id }}" {{  $tokeo->ct_predefined_comment_ids == $comment->id ? 'selected' : ''  }}  > {{ $comment->comment  }} </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                    </td>

                    @else
                    <td style="width: 40rem;">  {{ $tokeo->ct_comments  }}  </td>
                        @endif

                        @if (auth()->user()->hasRole('Head Master'))
                        <td style="width: 30rem;">

                            <select data-uuid={{ $tokeo->uuid }} data-student_id = '{{ $tokeo->student_id }}' data-type="HM" name="ct_comment[{{ $tokeo->student_id }}]" class="form-control hm_comment comments form-control-sm">
                                <option value=""> Select A Comment..... </option>
                                @foreach ($predefined_comments as $comment )
                                <option value="{{ $comment->id }}" {{  $tokeo->hm_predefined_comment_ids == $comment->id ? 'selected' : ''  }}  > {{ $comment->comment  }} </option>
                                @endforeach
                            </select>

                        </td>

                        @elseif ($tokeo->hm_comments)

                        <td> {{  $tokeo->hm_comments }} </td>

                        @endif

                    </tr>

                    @endforeach
                </tbody>


            </table>

            </form>

            </div>
        </div>


    </div>

    </div>
    </div>
</div>
</div>
{{--
<div class="row no-gutters d-block clearfix mt-4">
    <div class="card mg-b-30 shadow-1"> --}}


    <div class="nk-block nk-block-lg mt-4">
        <div class="row g-gs">
            <div class="col-md-6">
                <div class="card the-cards  card-preview">
                    <div class="card-inner">
                        <div class="card-header with-elements">
                            <h4 class="card-header-title">
                               Statistics
                            </h4>
                            <div class="card-header-btn">
                               <a href="javascript:void(0)" data-toggle="collapse" class="btn btn-info" data-target="#collapse1" aria-expanded="true"><i class="ion-ios-arrow-down"></i></a>
                               <a href="javascript:void(0)" data-toggle="refresh" class="btn btn-warning"><i class="ion-android-refresh"></i></a>
                               <a href="javascript:void(0)" data-toggle="expand" class="btn btn-success"><i class="ion-android-expand"></i></a>
                               <a href="javascript:void(0)" data-toggle="remove" class="btn btn-danger"><i class="ion-ios-trash-outline"></i></a>
                            </div>
                         </div>
                        <div class="nk-ck-sm">
                            <canvas class="this-pie-chart" id="barChart"></canvas>
                        </div>
                    </div>
                </div><!-- .card-preview -->
            </div>

            <div class="col-md-6">
                <div class="card the-cards  card-preview">
                    <div class="card-inner">
                        <div class="card-header with-elements">
                            <h4 class="card-header-title">
                               Statistics
                            </h4>
                            <div class="card-header-btn">
                               <a href="javascript:void(0)" data-toggle="collapse" class="btn btn-info" data-target="#collapse1" aria-expanded="true"><i class="ion-ios-arrow-down"></i></a>
                               <a href="javascript:void(0)" data-toggle="refresh" class="btn btn-warning"><i class="ion-android-refresh"></i></a>
                               <a href="javascript:void(0)" data-toggle="expand" class="btn btn-success"><i class="ion-android-expand"></i></a>
                               <a href="javascript:void(0)" data-toggle="remove" class="btn btn-danger"><i class="ion-ios-trash-outline"></i></a>
                            </div>
                         </div>
                        <div class="nk-ck-sm">
                            <canvas  id="subjectGradesChart"></canvas>
                        </div>
                    </div>
                </div><!-- .card-preview -->
            </div>

            <div class="col-md-6 mt-3">
                <div class="card the-cards card-preview">
                    <div class="card-inner">
                        <div class="card-header with-elements">
                            <h4 class="card-header-title">
                               Statistics
                            </h4>
                            <div class="card-header-btn">
                               <a href="javascript:void(0)" data-toggle="collapse" class="btn btn-info" data-target="#collapse1" aria-expanded="true"><i class="ion-ios-arrow-down"></i></a>
                               <a href="javascript:void(0)" data-toggle="refresh" class="btn btn-warning"><i class="ion-android-refresh"></i></a>
                               <a href="javascript:void(0)" data-toggle="expand" class="btn btn-success"><i class="ion-android-expand"></i></a>
                               <a href="javascript:void(0)" data-toggle="remove" class="btn btn-danger"><i class="ion-ios-trash-outline"></i></a>
                            </div>
                         </div>
                        <div class="nk-ck-sm">
                            <canvas class="this-pie-chart" id="pieChart"></canvas>
                        </div>
                    </div>
                </div><!-- .card-preview -->
            </div>

            <div class="col-md-6 mt-3">
                <div class="card the-cards  card-preview">
                    <div class="card-inner">
                        <div class="card-header with-elements">
                            <h4 class="card-header-title">
                               Statistics
                            </h4>
                            <div class="card-header-btn">
                               <a href="javascript:void(0)" data-toggle="collapse" class="btn btn-info" data-target="#collapse1" aria-expanded="true"><i class="ion-ios-arrow-down"></i></a>
                               <a href="javascript:void(0)" data-toggle="refresh" class="btn btn-warning"><i class="ion-android-refresh"></i></a>
                               <a href="javascript:void(0)" data-toggle="expand" class="btn btn-success"><i class="ion-android-expand"></i></a>
                               <a href="javascript:void(0)" data-toggle="remove" class="btn btn-danger"><i class="ion-ios-trash-outline"></i></a>
                            </div>
                         </div>
                        <div class="nk-ck-sm">
                            <canvas class="pie-chart" id="subjectGradesAverageChart"></canvas>
                        </div>
                    </div>
                </div><!-- .card-preview -->
            </div>




        </div>



    </div>

    </div>
 </div>





@section('scripts')

<script>

/* GRAPHS */


declare();

function declare(){

if (!window.isdeclared) {

let divisionCount;
let divisions;
let values;
let backgroundColors;
let barChartVariable;
let pieChart;
let gradeLabels;
window.isdeclared = true;

}

}


if (!window.divisionChartLoaded) {
    divisionCount = @json($divisionCount);
    divisions = Object.keys(divisionCount);
    values = Object.values(divisionCount);

    backgroundColors = divisions.map((division, index) => {
        switch (division) {
            case 'Division 1':
                return '#069613';
            case 'Division I':
                return '#069613';
            case 'Division 2':
                return '#00579b';
            case 'Division II':
                return '#00579b';
            case 'Division 3':
                return '#21a5ba';

            case 'Division III':
                return '#21a5ba';

            case 'Division 4':
                return '#f9b943';

            case 'Division IV':
                return '#f9b943';

            case 'Division 0':
                return '#e36e60';
            default:
                return '#000000'; // Default color
        }
    });

    if (window.barChartVariable) {
        window.barChartVariable.destroy();
    }
    if (window.subjectGradesChartVariable) {
        window.subjectGradesChartVariable.destroy();
    }
    if (window.pieChartVariable) {
        window.pieChartVariable.destroy();
    }

    window.divisionChartLoaded = true;
}

function createBarChart() {
    const ctx = document.getElementById('barChart');

    barChartVariable = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: divisions,
        datasets: [{
            data: values,
            backgroundColor: backgroundColors,
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'DIVISION SUMMARY CHART'
            },
            legend: {
            display: false
        }
        }
    }
});

}

function createPieChart() {
    const ctx = document.getElementById('pieChart');

    pieChartVariable = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: divisions,
            datasets: [{
                label: '# of Students',
                data: values,
                backgroundColor: backgroundColors,
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'PERCENTAGE PERFOMANCE'
                }
            }
        }
    });
}

function getColor(grade){

    switch (grade) {
            case 'A':
                return '#069613';
            case 'B':
                return '#00579b';
            case 'C':
                return '#21a5ba';
            case 'D':
                return '#f9b943';
            case 'E':
                return '#fcd000';
            case 'S':
                return '#f1b560';
            case 'F':
                return '#d63601';
            default:
                return '#000000'; // Default color
        }

}


function subjectAverageGradeChart(){

    let subjectAverages = @json($processGradeAverages);

        // Extract labels and data for the chart
        let labels = Object.keys(subjectAverages);
        let data = Object.values(subjectAverages).map(subject => subject.average);
        let sortedSubjects = labels.slice().sort((a, b) => subjectAverages[b].average - subjectAverages[a].average);

        gradeLabels = @json($gradeLabels);
        let datasets = [{
            label: 'AVERAGE',
            data: sortedSubjects.map(subject => subjectAverages[subject].average),
            borderWidth: 1,
        }];

        // Your horizontal bar chart configuration
        let ctx = document.getElementById('subjectGradesAverageChart').getContext('2d');
        let subjectAverageGradeChartVariable = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100 // Assuming percentage scale
                    },
                    y: {
                        stacked: true
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'SUBJECTS PERFORMANCE RANK'
                    }
                }
            }
        });
}



function subjectGradesChart(){
    let subjectData = @json($processedData);
        let labels = Object.keys(subjectData);
        gradeLabels = @json($gradeLabels);

        let datasets = gradeLabels.map(grade => ({
            label: 'Grade ' + grade,
            backgroundColor: getColor(grade),
            borderColor: getColor(grade),
            borderWidth: 1,
            data: labels.map(subject => subjectData[subject][grade] || 0)
        }));

        // Your horizontal bar chart configuration
        var ctx = document.getElementById('subjectGradesChart').getContext('2d');
        var subjectGradesChartVariable = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {

indexAxis: 'y',

    elements: {
      bar: {
        borderWidth: 1,
      }
    },
    responsive: true,
    plugins: {
      legend: {
        position: 'right',
      },
      title: {
        display: true,
        text: 'SUBJECTS PERFORMANCE BY GRADE'
      }
    }
  },
        });



    }

subjectGradesChart();
subjectAverageGradeChart();
createBarChart();
createPieChart();


/* END GRAPHS */


$('.edit-ct').click(function(){

$(this).parent().addClass('d-none');
$(this).parent().parent().find('.toggle').removeClass('d-none');


})


$('.comments').change(function() {

    spark();
            let selectedValue = $(this).val();
            let studentId = $(this).data('student_id');
            let report_uuid = $(this).data('uuid');
            let type = $(this).data('type');

            $.ajax({
                type: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                url: '{{ route('results.reports.escalation.hm.comment') }}',
                data: {
                    student_id: studentId,
                    selected_value: selectedValue,
                    type:type,
                    report_uuid : report_uuid
                },
                success: function(response) {

                    console.log(response)

                },
                error: function(xhr, status, error) {
                    console.log(error)
                }
            });
            unspark();

        });


$('.escalate_report').click(function(){
    spark();
let ct_comment = $('.ct_comment').val();
let uuid = $(this).data('uuid');
let signature = $('#include-signature').is(':checked') ? $('#include-signature').val() : 0;

$.ajax({
url: '{{ route('results.reports.escalation.top')  }}',
method:'POST',
data:{
    uuid : uuid,
    ct_comment:ct_comment,
    signature:signature
},

beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

success:function(res){
    $('.escalate_report').addClass('disabled');
    if (res.title) {
        showNotification(res.msg,res.title);
    }


},

error: function(res){

    console.log(res)

}


})

unspark();

})

/* ATTEMPT TO PRINT ALL DAMN MOFAKAS --BEIRUT */
$('#pdfButton').hide();

$('#pdfButton').click(function() {

    let student_ids = [];
    spark();


    $(this).prop('disabled', true);
    $('.other').each(function() {
        let elem = $(this);
        if (elem.prop('checked')) {
            student_ids.push(elem.val());
        }
    });

    let our_token = $('#our_token').val();

    $.ajax({
        url: '{{ route('results.reports.generated.single.exam.report.pdf') }}',
        type: 'POST',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

        data: {
            student_ids: student_ids,
            our_token : our_token
        },
        success: function(res) {
                // let pdfUrl = res.pdf_url;
                let modal = $('<div class="modal" id="pdfModal" tabindex="-1" role="dialog">');
                    // console.log('res',res)
                    let iframes = '';
                    // res.reports.forEach(pdfUrl => {
                      let path =  '{{ asset("reports/temp") }}'+ '/'+ res.reports;
                    //   console.log('path',path);
                        iframes = `<iframe id="pdfFrame" src="${path}" width="100%" style=" height:95vh !important"></iframe>`;
                    // });

                    console.log(iframes);

                modal.html(`<div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-body"> ${iframes} </div></div> </div>`);

                $('body').append(modal);
                modal.modal('show');

                modal.on('hidden.bs.modal', function () {
                modal.remove();

                });

            //     $('#pdfFrame').on('load', function() {
            //     var contentWindow = this.contentWindow;
            //     if (contentWindow) {
            //     contentWindow.print();

            //     // modal.modal('hide');
            //     // setTimeout(() => {
            //     //     modal.remove();
            //     // }, 200);

            //     }
            // });

                unspark();
    },
    error:function(res){
        unspark();
    }

    });


});


/* END OF AN ATTEMPT */


$('.other').change(function(){

$('.checkall').addClass('minus').prop('indeterminate',true);

if ($('#results input[type="checkbox"]:checked').length === 0) {
    $('.checkall').prop('indeterminate', false);
    $('#excelButton').hide('slow');
    $('#pdfButton').hide('slow');

}else{
    $('#excelButton').show('slow');
    $('#pdfButton').show('slow');
}




})

$('.checkall').change(function(){

if($(this).prop('indeterminate')){
    $('#results input[type="checkbox"]').prop('checked', false);

}else{
    $('#results input[type="checkbox"]').prop('checked', this.checked);
    $('#excelButton').show('slow');
    $('#pdfButton').show('slow');
}


});



</script>


@endsection
@endsection



















