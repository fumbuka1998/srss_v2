<style>
    .table-container {

        overflow-x: scroll;
        /* Enable horizontal scroll */
        position: relative;
    }

    .tabloid th {
        background-color: #069613;
        /* Header background color */
        color: #fff;
        /* Header text color */
    }

    /*
    /* Freeze the first 3 columns */
    .table-wrapper .frozen {
        position: sticky;
        left: 0;
        z-index: 2;
        /* background-color: #fff; */

    }

    .table-wrapper .frozen-td {
        background-color: #fff;
    }

    .the-cards {

        max-width: 68rem;
        margin: auto;
        /* min-height: 29rem; */
        /* max-height: 29rem; */
    }

    .this-pie-chart {
        width: 100%;
        /* Make the canvas width 100% of the container */
        height: auto;
        /* Allow the height to adjust proportionally */
        max-height: 100%;
        /* Set a maximum height equal to the container's height */
        /* display: block;  */
        margin: 0 auto;
        /* Center the chart within its container */
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
                        <th> {{ $year_name }} </th>
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
                    {{-- <a href="javascript:void(0)" title="Generate Report"  style="color:white"  class="generate_dynamic_single_exam_report btn btn-success"> <i class="fa-solid fa-file-lines"></i> Create  Report </a> --}}
                    <a href="javascript:void(0)"
                        class="btn btn-info btn-with-icon btn-block generate_dynamic_multiple_report">
                        <div class="ht-40 justify-content-between">
                            <span class="pd-x-15">Create Report</span>
                            <span class="icon wd-40"><i class="fa fa-file-lines"></i></span>
                        </div>
                    </a>
                    <a href="javascript:void(0)" title="pdf" style="color:white; display:none"
                        class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Pdf </a>
                </span>

            </div>


            <div class="table-container">
                <div class="table-wrapper">

                    <form id="generate_dynamic_multiple_report">

                        <input type="hidden" id="exam" name="exam_type[]" value="{{ encrypt($exam_type) }}">
                        <input type="hidden" id="class_id" name="class_id" value="{{ $class_id }}">
                        <input type="hidden" id="stream_id" name="stream_id" value="{{ $stream_id }}">
                        <input type="hidden" id="subject_id" name="subject_id" value="{{ $subject_id }}">
                        <input type="hidden" id="report_name" name="report_name" value="{{ $report_id }}">
                        <input type="hidden" id="for_my_grade" name="for_my_grade" value="{{ $for_my_grade }}">
                        <input type="hidden" id="academic_year_id" name="academic_year" value="{{ $academic_year }}">
                        <input type="hidden" id="term_id" name="term_id" value="{{ $semester }}">
                        <input type="hidden" id="metadata" name="metadata" value={{ $jt }}>

                        <table class="table tabloid display compact responsive table-bordered" id="table"
                            style="width:100%">

                            <thead>
                                <tr>
                                    <th class="frozen" rowspan="3">SN</th>
                                    <th rowspan="3" class="frozen" style="text-align: center; width:20rem">ADMISSION
                                        NUMBER</th>
                                    <th style="min-width:21rem" class="text-center frozen" rowspan="3">FULL NAME</th>
                                    <th style="text-align:center" class="text-center" colspan="{{ $colspan }}">
                                        SUBJECTS
                                    </th>
                                    <th style="text-align:center" rowspan="3"> AVG </th>
                                    <th style="text-align:center" rowspan="3"> GRD </th>
                                    <th style="text-align:center" rowspan="3"> DIV </th>
                                    <th style="text-align:center" rowspan="3"> POINTS </th>
                                    <th style="text-align:center" rowspan="3"> C/T Comments </th>
                                </tr>
                                <tr>

                                    @foreach ($sbjct_columns as $sbjct)
                                        <th style="text-align:center" colspan="{{ $sbjct['subject_span'] }}"
                                            data-subject_uuid="{{ $sbjct['subject_uuid'] }}"> {{ $sbjct['name'] }}
                                        </th>
                                    @endforeach


                                </tr>
                                @for ($i = 0; $i < count($sbjct_columns); $i++)

                                    @foreach ($exam_type_columns as $key => $coltype)
                                        @if ($sbjct_columns[$i]['subject_id'] == $coltype['subject_id'])
                                            <th>{{ $coltype['exam']->code . '/ ' . $coltype['exam']->total_marks }}</th>
                                        @endif
                                    @endforeach
                                    <th style="text-align:center" data-subject_id="" class="text-center">AVG %</th>
                                    <th style="text-align:center" data-subject_id="" class="text-center">GRADE</th>
                                    <th style="text-align:center" data-subject_id="" class="text-center">POINTS</th>

                                @endfor

                            </thead>



                            <tbody>



                                @foreach ($students as $key => $student)
                                    @php
                                        $avg = 0;
                                        $checkpoints = 0;
                                    @endphp


                                    <tr>
                                        <td> {{ ++$key }} </td>
                                        <td> {{ $table_items[$student->id]['admission_no'] }} </td>
                                        <td class="frozen frozen-td">
                                            {{ strtoupper($table_items[$student->id]['full_name']) }}</td>

                                        @if (count($table_items[$student->id]['results']))
                                            @for ($k = 0; $k < count($sbjct_columns); $k++)
                                                @foreach ($exam_types as $column)
                                                    @php
                                                        $score = '-';
                                                        $check['AVG'] = '-';
                                                        $check['POINT'] = '-';
                                                        $check['GRADE'] = '-';
                                                    @endphp

                                                    @if (isset($table_items[$student->id]['results'][$sbjct_columns[$k]['subject_id']]))
                                                        @php
                                                            $check =
                                                                $table_items[$student->id]['results'][
                                                                    $sbjct_columns[$k]['subject_id']
                                                                ];
                                                            if (isset($check[$column])) {
                                                                $score = $check[$column];
                                                            }
                                                        @endphp
                                                    @endif

                                                    <td> {{ $score }} </td>
                                                @endforeach

                                                @php
                                                    $avg += floatval($check['AVG']);
                                                    $checkpoints += floatval($check['POINT']);
                                                @endphp


                                                <td> {{ $check['AVG'] }} </td>
                                                <td> {{ $check['GRADE'] }}</td>
                                                <td> {{ $check['POINT'] }} </td>
                                            @endfor


                                            <td> {{ $table_items[$student->id]['AVG'] }} </td>
                                            <td> {{ $table_items[$student->id]['GRADE'] }}</td>
                                            <td> {{ $table_items[$student->id]['CODE'] }} </td>
                                            <td> {{ $table_items[$student->id]['POINTS'] }} </td>
                                            <td style="min-width: 20em">
                                                <select name="ct_comment[{{ $student->id }}]"
                                                    class="form-control select2s form-control-sm">

                                                    <option value="">Select Comment</option>
                                                    @foreach ($predefined_comments as $comment)
                                                        <option value="{{ $comment->id }}">{{ $comment->comment }}
                                                        </option>
                                                    @endforeach

                                                </select>
                </div>
                </td>
                </tr>
            @else
                @for ($k = 0; $k < count($sbjct_columns); $k++)
                    @foreach ($exam_types as $column)
                        <td>-</td>
                    @endforeach


                    <td> {{ $check['AVG'] }} </td>
                    <td> {{ $check['GRADE'] }}</td>
                    <td> {{ $check['POINT'] }} </td>
                @endfor

                <td> {{ $table_items[$student->id]['AVG'] }} </td>
                <td> {{ $table_items[$student->id]['GRADE'] }}</td>
                <td> {{ $table_items[$student->id]['CODE'] }} </td>
                <td> {{ $table_items[$student->id]['POINTS'] }} </td>
                <td style="min-width: 15em">
                    <select name="ct_comment[{{ $student->id }}]" class="form-control select2s form-control-sm">
                        <option value="">Select Comment</option>
                        @foreach ($predefined_comments as $comment)
                            <option value="{{ $comment->id }}">{{ $comment->comment }}</option>
                        @endforeach

                    </select>
            </div>
            </td>
            </tr>
            @endif


            </tr>


            @endforeach {{-- students foreach closure --}}


            {{-- @foreach ($students as $index => $student)
                    <tr>
                        <td>{{ ++$index }}</td>
                        <td> {{ $student->admission_no }}  </td>
                        <td> {{ $student->full_name }}  </td>
                    </tr>
                    @endforeach --}}
            </tbody>

            </table>

            </form>

        </div>
    </div>






    <script>
        $(document).ready(function() {


            $('.select2s').select2({
                width: '100%'
            })


        })
    </script>
