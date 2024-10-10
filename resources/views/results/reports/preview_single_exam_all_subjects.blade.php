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

.the-cards {

    max-width: 68rem;
    margin: auto;
    /* min-height: 29rem; */
    /* max-height: 29rem; */
}

.this-pie-chart {
    width: 100%; /* Make the canvas width 100% of the container */
    height: auto; /* Allow the height to adjust proportionally */
    max-height: 100%; /* Set a maximum height equal to the container's height */
    /* display: block;  */
    margin: 0 auto; /* Center the chart within its container */
}




</style>

<div class="card">
    <div class="card-header">
        REPORT PREVIEW
    </div>

    <div class="card-body">
    <div class="row clearfix">

        <div class="col-md-6 ">
            <span class="mg-y-10 tx-13 tx-semibold tx-uppercase tx-black d-block"> QUICK LOOKUP </span>
            <table class="responsive compact display table">
                <tr>
                    <th> <i class="fa fa-circle tx-info"></i> Year</th>
                    <th>  {{ $year_name }} </th>
                </tr>
                <tr>
                    <th> <i class="fa fa-circle tx-teal"></i> Term</th>
                    <th> {{ $semester_name }} </th>
                </tr>
                <tr>
                    <th> <i class="fa fa-circle tx-purple"></i> Class </th>
                    <th> {{ $class_name }} </th>
                </tr>
                <tr>
                    <th> <i class="fa fa-circle tx-orange"></i> Report </th>
                    <th> {{ $report }} </th>
                </tr>
            </table>
        </div>

        <div class="col-md-6 d-flex align-items-center justify-content-end">
            <span style="float:right" class="mt-8">
                {{-- <a href="javascript:void(0)" title="pdf"  style="color:white; "  class="btn btn-warning btn-sm" onclick="printPdf()"><i class="fa fa-print"></i> Pdf </a> --}}

            {{-- <a href="javascript:void(0)" title="Generate Report"  style="color:white"  class="generate_dynamic_single_exam_report btn btn-success"> <i class="fa-solid fa-file-lines"></i> Create  Report </a> --}}
            <a href="javascript:void(0)" class="btn btn-info btn-with-icon btn-block generate_dynamic_single_exam_report">
                <div class="ht-40 justify-content-between">
                   <span class="pd-x-15">Create Report</span>
                   <span class="icon wd-40"><i class="fa fa-file-lines"></i></span>
                </div>
             </a>
            </span>

            </div>

        <div class="table-container">
            <div class="table-wrapper">
                <form id="single_exam_type_no_subject">
                    <input type="hidden" id="exam" name="exam_type[]" value='{{encrypt($exam_type) }}'>
                    <input type="hidden" id="class_id" name="class_id" value="{{ $class_id }}">
                    <input type="hidden" id="stream_id" name="stream_id" value="{{ $stream_id }}">
                    <input type="hidden" id="academic_year_id" name="academic_year" value="{{ $academic_year }}">
                    <input type="hidden" id="report_name" name="report_name" value="{{ $report_name }}">
                    <input type="hidden" id="term_id" name="term_id" value="{{ $semester }}">
                    <input type="hidden" value="{{ encrypt($matokeo)  }}" name="metadata" id="metadata">
                    <input type="hidden" id="division_count" value="{{ json_encode($divisionCount) }}">
                    <input type="hidden" name="for_my_grade" id="for_my_grade" value="{{ $for_my_grade }}">

                <table class="table tabloid display compact responsive table-bordered" id="table" style="width:100%">

                    <thead>
                        <tr>
                            <th rowspan="3">SN</th>
                            <th class="" rowspan="3" style="text-align: center;">ADMISSION NUMBER</th>
                            <th style="min-width:25rem" class="text-center frozen" rowspan="3">FULL NAME</th>
                            <th style="text-align:center" class="text-center"  colspan="{{ $colspan }}">
                            SUBJECTS  (x/ {{ $examInfo->total_marks }})
                            </th>
                            <th style="text-align:center" rowspan="3"> AVG </th>
                            <th style="text-align:center" rowspan="3"> DIV </th>
                            <th style="text-align:center" rowspan="3"> POINTS </th>
                            <th style="text-align:center; min-width: 10rem;" rowspan="3"> REMARKS </th>
                            <th style="text-align:center; min-width:22rem"  rowspan="3"> C/T Comments </th>
                            {{-- <input type="hidden" id="division_count" value="{{ json_encode($divisionCount }}"> --}}
                        </tr>

                        <tr>
                       @foreach ( $sbjct_columns as $sbtjct_column )
                       <th colspan="3" style="text-align:center" data-subject_id="{{ $sbtjct_column['subject_id'] }}" class="text-center">{{ $sbtjct_column['name'] }}</th>
                       @endforeach
                        </tr>

                        <tr>
                            @foreach ($sbjct_columns as $sbtjct_column )
                              <th style="text-align:center" data-subject_id="'.$sbtjct_column['subject_id'].'" class="text-center">Marks/{{ $examInfo->total_marks }}</th>
                               <th style="text-align:center" data-subject_id="'.$sbtjct_column['subject_id'].'" class="text-center">%</th>
                              <th style="text-align:center" data-subject_id="'.$sbtjct_column['subject_id'].'" class="text-center">Grade</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $count = 0;
                        @endphp

                        @foreach ($matokeo as $key => $tokeo )

                        @php
                        $results =  isset($tokeo['results']) ? $tokeo['results'] :  array(); ;
                        @endphp

                        <tr>
                            <td>{{ ++$count  }}</td>
                            <td class=""> {{ $tokeo['admission_no'] }} </td>
                            <td class="frozen frozen-td"> {{ $tokeo['full_name']  }}</td>

                            @for ($i=0; $i<count($sbjct_columns);  $i++)

                            {{-- @foreach ($exam_type as $key => $column) --}}
                            @php
                             $subject_id = $sbjct_columns[$i]['subject_id'];
                                $score = '-';
                                $percent = '-';
                                $grade = '-';

                                    if (isset($results[$subject_id])) {
                                      $score =  $results[$subject_id]['score'];
                                      $percent =  $results[$subject_id]['percent'];
                                      $grade =  $results[$subject_id]['grade'];
                                }
                            @endphp

                            <td> {{ $score }} </td>
                            <td> {{ $percent }} </td>
                            <td> {{ $grade }} </td>

                            @endfor

                            <td> {{ $tokeo['AVG'] }} </td>
                            <td> {{ $tokeo['CODE'] }} </td>
                            <td> {{ $tokeo['POINTS'] }} </td>
                            <td>{{ $tokeo['REMARKS'] }}</td>

                            <td>
                                <div class="form-group">
                                    <select name="ct_comment[{{$tokeo['admission_no']}}]" class="form-control select2s">
                                        <option> Add Comment.... </option>
                                        @foreach ($predefined_comments as $key => $pre)
                                        <option value="{{ $pre->id }}">{{ $pre->comment }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
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



    <div class="row no-gutters d-block clearfix">
        <div class="card mg-b-30 shadow-1">
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

        <div class="nk-block nk-block-lg">
            <div class="row g-gs">
                <div class="col-md-6">
                    <div class="card the-cards  card-preview">
                        <div class="card-inner">
                            <div class="card-head">
                                <h6 class="title"></h6>
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
                            <div class="card-head text-center">
                                <h6 class="title"></h6>
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
                            <div class="card-head text-center">
                                <h6 class="title"></h6>
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
                            <div class="card-head text-center">
                                <h6 class="title"></h6>
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


     <script>




$('.select2s').select2();

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
    divisionCount = JSON.parse($('#division_count').val());
    divisions = Object.keys(divisionCount);
    values = Object.values(divisionCount);

    backgroundColors = divisions.map((division, index) => {
        switch (division) {
            case 'Division 1':
                return '#069613';
            case 'Division 2':
                return '#00579b';
            case 'Division 3':
                return '#21a5ba';
            case 'Division 4':
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


// printing the score sheet

function printPdf() {
    // Hide elements except for the table-container div
    var elementsToHide = document.querySelectorAll('body > :not(.table-container)');
    for (var i = 0; i < elementsToHide.length; i++) {
        elementsToHide[i].style.display = 'none';
    }

    // Change orientation to landscape
    var style = document.createElement('style');
    style.innerHTML = '@page { size: landscape; }';
    document.head.appendChild(style);

    // Print the table-container div
    window.print();

    // Remove landscape orientation style
    document.head.removeChild(style);

    // Show the hidden elements again
    for (var i = 0; i < elementsToHide.length; i++) {
        elementsToHide[i].style.display = '';
    }
}




     </script>





















