<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        body {
            /* min-height: 100rem; */
            margin: 0 auto;
            color: #000000;
            background: #FFFFFF;
            font-family: "DejaVu Sans Mono";
            font-size: 12px;
            position: relative;
        }


        body p {
            text-indent: 8px;
            color: #000000;
            /*font-family: "Times New Roman Georgia";*/
            font-size: 11px;
        }

        body p .text-val {
            text-transform: uppercase;
            font-size: 12px;
            /*text-decoration: underline;*/
        }

        .address {
            font-family: "DejaVu Sans Mono";
            /* font-size: 10px; */
            letter-spacing: 0.15px;
            color: #000000;
        }

        .address strong {
            font-size: 12px;
            color: #bc1f27;
        }

        header {
            padding: 4px 0;
            margin-bottom: 4px;
        }

        #logo {
            text-align: center;
        }

        #logo img {
            width: 100px;
        }

        h1 {
            border-top: 1px solid #000000;
            border-bottom: 1px solid #000000;
            color: #000000;
            font-size: 1.5em;
            line-height: 1.4em;
            font-weight: normal;
            text-align: center;
            margin: 0 0 2px 0;
        }

        .table1 {
            border: 1px solid #000000;
            /*border-bottom: 1px solid #000000;*/
            /* border-left: 0; */
            font-family: "DejaVu Sans Mono";
            width: 100%;
            border-collapse: collapse;
        }

        .table1 td {
            /* border: 1px solid #000000; */
            border-right: 1px solid #000000;
            padding: 5px;
            line-height: 12px;
            font-size: 10px;
        }

        .table1 table td {
            border-left: 0;
        }

        .table1 th {
            padding: 4px;
            line-height: 13px;
            font-size: 12px;
        }

        .table1 thead th {
            border: 1px solid #000000;
            /* border-bottom: 1px solid #000000; */
        }

        .table1 thead th:last-child {
            border: 1px solid #000000;
        }

        .table1 tbody td:last-child {
            border: 1px solid #000000;
        }



        /*#header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }*/
        .footer_ {
            position: fixed;
            left: 0px;
            /*padding: 8px 0;*/
            text-align: center;
            bottom: 0px;
            right: 0px;
            height: 150px;
            color: #000000;
            width: 100%;
            border-top: 1px solid #C1CED9;
        }

        .footer_ .page:after {
            content: counter(page, upper-roman);
        }

        .footer__ {
            width: 100%;
            position: fixed;
            left: 0px;
            bottom: 0px;
            right: 0px;
            height: 60px;
            text-align: center;
            color: #000000;

        }

        .footer__ .page:after {
            content: counter(page, upper-roman);
        }

        .space:before {
            content: " ";
            padding-right: 120px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px
        }

        .space-no-underline:before {
            content: " ";
            padding-right: 100px;
        }

        .aaa {
            display: flex;
            align-items: center;
        }

        #cover_page {
            position: absolute;
            left: 89px;
            top: 370px;
            letter-spacing: -0.34px;
            z-index: -3;
            opacity: 0.1;
        }

        /* my new css */

        .card {
            border: 1px solid #000;
            padding: 5px;
        }

        .table2 {
            width: 100%;
            border-collapse: collapse;
        }

        .table2 td {
            width: 8%;
            white-space: nowrap;
            padding: 3px;
            line-height: 8px;
        }

        .table2,
        .table2 td {
            border: 1px solid black;
        }

        .table3 {
            width: 100%;
            border-collapse: collapse;
        }

        .table3 td {
            width: 5%;
            white-space: nowrap;
            padding: 2px;
            line-height: 10px;
        }

        .table3,
        .table3 td {
            border: 1px solid black;
        }
    </style>
    <title>Results</title>
</head>

<body>
    <span id="cover_page" style="position: absolute; align-items:center">
        {{-- <img height="350" width="500" src="{{ asset('assets/logo/sbrt_logo.gif') }}" alt=""> --}}
        <img src="{{ public_path('assets/logo/sbrt_logo.gif') }}" alt="Logo" height="350" width="500" >
    </span>

    <header class="clearfix">

        <div id="logo">
            <div style="float: left;">
                {{-- <img src="{{ asset('assets/logo/sbrt_logo.gif') }}" alt=""> --}}
                <img src="{{ public_path('assets/logo/sbrt_logo.gif') }}" alt="Logo" style="width:100px;">
            </div>
            <div class="address" style="float: left; margin-left: 20px; line-height: 16px;">
                <h2 style="color: #040404; margin-bottom:-4px; letter-spacing: 2px;">SHAABAN ROBERT SECONDARY SCHOOL
                </h2>
                <span style="color: #000000; font-size: 11.5px">P.0 BOX 736, DAR ES SALAAM TANZANIA</span> <br>
                <span style="color: #000000; font-size: 11.5px">Tel: +(255) 22 2114903, (+255) 22 21 14 935</span>
                <br />
                <span style="font-size: 11px"> email:info@shaabanrobert.sc.tz &nbsp; WEBSITE: www.shaabanrobert.sc.tz
                </span>
            </div>
            {{-- <span>
                {{$rst[$student_id]['profile_pic']}}
                background-image:url('{{ asset($rst[$student_id]['profile_pic']) }}');
            </span> --}}

            <div style="float: right;">

                @if ($rst[$student_id]['profile_pic'])
                    <div
                        style="
                width: 6rem;
                height: 6rem;
                border-radius: 50%;
                overflow: hidden;
                background-image: url('{{ asset('storage/' . $rst[$student_id]['profile_pic']) }}');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                ">
                    </div>
                @else
                    <div
                        style="
                width: 6rem;
                height: 6rem;
                border-radius: 50%;
                overflow: hidden;
                ">
                        <img style="
                width: 100%;
                height: auto;
                "
                            src="{{ public_path('assets/img/icon_avatar.jpeg') }}" alt="Profile Picture">
                    </div>
                @endif
            </div>
        </div>
    </header>
    <hr>
    <div>
        <h4 style="font-size: 15px; text-align:center; margin-top:-2px">{{ strtoupper($exam_report->name) }} RESULTS
        </h4>
        <p style="font-size: 12px; margin-top:-18px; text-align:center"> {{ strtoupper($semester->name) }} </p>
    </div>
    <div>



        <div class="aaa">
            <table>
                <tr>
                    <td>NAME:</td>
                    <td style="min-width:200px; max-width:250px;"> <strong> <i>
                                {{ strtoupper($rst[$student_id]['results']->full_name) }} </i> </strong> </td>

                    <td style="padding-left:40px">CLASS:</td>
                    <td> <strong>{{ strtoupper($school_class->name . ' ' . $stream->name) }}</strong> </td>

                    <td style="padding-left:40px"> REGN: </td>
                    <td> <strong> <i> {{ $student_reg }} </i> </strong> </td>

                    <td style="float: right">YEAR: </td>
                    <td> <strong> <i> {{ $year->name }} </i> </strong> </td>
                </tr>
            </table>
        </div>
    </div>

    <table class="table1" style="width:100%; margin-top: 10px" border="1">
        <thead>
            <tr>
                <th rowspan="2" style="text-align: left;font-size: 11px">SUBJECTS</th>
                <th colspan="{{ $span }}"> DAILY PROGRESS </th>
                @foreach ($exams as $key => $exam)
                    @if (!$exam->is_dp)
                        <th rowspan="2">{{ $exam->code }} / {{ $exam->total_marks }}</th>
                    @endif
                @endforeach
                <th rowspan="2" style="text-align:center" data-subject_id="" class="text-center">AVG %</th>
                <th rowspan="2" style="text-align:center" data-subject_id="" class="text-center">GRD</th>
                {{-- <th rowspan="2" style="text-align:center" data-subject_id="" class="text-center">PNTS</th> --}}
                {{-- <th colspan="2" rowspan="" style="text-align: left;font-size: 11px">RANKS </th>
            <th rowspan="" style="text-align: left;font-size: 11px">TOP</th> --}}
                <th rowspan="2" colspan="3" style="text-align: left;font-size: 11px" class="text-center">REMARKS
                </th>
            </tr>

            <tr>
                @foreach ($exams as $key => $exam)
                    @if ($exam->is_dp)
                        <th> {{ $exam->code }} / {{ $exam->total_marks }}</th>
                    @endif
                @endforeach
                {{-- <th>SP</th>
            <th>CP</th> --}}
                {{-- <th>SCORE</th> --}}
            </tr>
        </thead>

        <tbody>


            @foreach ($rst[$student_id]['subjects'] as $subject)
                @php
                    $subject_id = $subject->sbjct_id;
                    $score = '-';
                    $check_module = $rst[$student_id]['metadata']->results;
                @endphp

                <tr>
                    @if (isset($check_module->$subject_id))
                        <td>{{ $subject->subject_name }}</td>
                        @php
                            $points = isset($rst[$student_id]['metadata']->results->$subject_id)
                                ? $rst[$student_id]['metadata']->results->$subject_id->POINT
                                : '-';
                            $avg = isset($rst[$student_id]['metadata']->results->$subject_id)
                                ? $rst[$student_id]['metadata']->results->$subject_id->AVG
                                : '-';
                            $grade = isset($rst[$student_id]['metadata']->results->$subject_id)
                                ? $rst[$student_id]['metadata']->results->$subject_id->GRADE
                                : '-';
                            $remarks = isset($rst[$student_id]['metadata']->results->$subject_id)
                                ? $rst[$student_id]['metadata']->results->$subject_id->REMARKS
                                : '-';
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
                    {{-- <td style="text-align:center">{{ $points }}</td> --}}
                    <td colspan="3">{{ $remarks }}</td>
                </tr>
            @endforeach
            <tr>
                <td>Total/avg/Grade</td>
                <td colspan="{{ $span  }}"></td>
                <td style="text-align: center">{{ $rst[$student_id]['metadata']->AVG }}</td>
                <td style="text-align: center">{{ $rst[$student_id]['metadata']->GRADE }}</td>
                <td></td>
                <td rowspan="4" colspan="3"></td>
            </tr>

            <tr>
                <td>DIVISION</td>
                <td style="text-align: center" colspan="{{ $span }}">
                    {{ $rst[$student_id]['results']->division }}</td>
                <td style="text-align: center" colspan="{{ $span  }}">POINTS</td>
                <td style="text-align: center"> {{ $rst[$student_id]['results']->points }}</td>
            </tr>

            <tr>
                <td>Attendance (x/y)</td>
                <td colspan="{{ $span }}"  style="text-align: center">{{$attendance}}</td>
                <td colspan="{{ $span + 1 }}" style="text-align: center">{{$total_days}}</td>
            </tr>

            <tr>
                <td>No Of Days Late (x/y)</td>
                <td colspan="{{ $span }}" style="text-align: center">{{$late}}</td>
                <td colspan="{{ $span + 1 }}" style="text-align: center">{{$total_days}}</td>
            </tr>










            {{--
    <tr>
        <td rowspan="3">Class Teachers Comment</td>
        <td rowspan="3"></td>
    </tr> --}}

            {{--
    <tr>

        <td>Class Teachers Name</td>
        <td>Signature</td>
        <td>  </td>

    </tr>


    <tr>

        <td>Parents Name</td>
        <td>Signature</td>
        <td>  </td>

    </tr> --}}



        </tbody>

    </table>


    <br>
    <div class="card">
        @if ($have_ca === 1)
            <table style="width: 600px">
                <tr>
                    <td style="width: 250px">

                            <table class="table2" border="1">
                                <thead>
                                    <tr>
                                        <th colspan="{{ count($ca) + 1 }}" style="text-align: center;">Character
                                            Assessments</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Code:</td>
                                        @foreach ($ca as $cas)
                                            <td>{{ $cas->code }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <td>Grade:</td>
                                        @foreach ($ca as $cas)
                                            <td>{{ $cas->grade }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        {{-- @endif --}}


                    </td>
                    <td style="width: 150px">

                        {{-- @if ($have_ca == 1) --}}
                            <table class="table3" border="1" style="text-align:center">
                                <tbody>
                                    <tr>
                                        <td>A</td>
                                        <td>B</td>
                                        <td>C</td>
                                        <td>D</td>
                                        <td>F</td>
                                    </tr>
                                    <tr>
                                        <td>100-75</td>
                                        <td>74-65</td>
                                        <td>64-45</td>
                                        <td>44-30</td>
                                        <td>29-0</td>
                                    </tr>
                                    <tr>
                                        <td>Excellent</td>
                                        <td>Very Good</td>
                                        <td>Good</td>
                                        <td>Satisfactory</td>
                                        <td>Fail</td>
                                    </tr>
                                </tbody>
                            </table>

                    </td>
                </tr>
            </table>
        @endif


        <table style="width: 600px; ">

            <tr>
                <td style="width: 250px">
                    <table class="table4" border="0">
                        <thead>
                            <tr>
                                {{-- <th style="text-align: center;">Class Teacher's Remarks: </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Class Teacher's Remarks :</td>
                                <td>{{ $comment }}</td>
                            </tr>
                            <tr>
                                <td>C.T Name:</td>
                                <td>{{ $class_teacher->full_name }}</td>
                            </tr>
                            <tr>
                                <td>C.T Signature :</td>
                                <td>
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td>Parents Name :</td>
                                <td>
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td>Signature :</td>
                                <td>
                                    <hr>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 150px">
                    <table class="table5" border="0" style="text-align:center">
                        <tbody>
                            <tr>
                                <td>QUALITY INDEX:</td>
                                <td>
                                    {{ $quality_index }}
                                </td>
                            </tr>
                            @if ($is_signature)
                            <tr>
                                <td>H.M's Signature:</td>
                                    <td>
                                        <div style="display:flex; justify-content:space-between; align-items:center">
                                            {{-- <p style="">HM Signature:</p>--}} &nbsp;
                                             <img style="height:40px;"
                                                src="{{ asset('assets/signature/hm_signature.png') }}"
                                                alt="">
                                            <span> {{ date('jS F Y') }} </span>
                                        </div>

                                    </td>

                            </tr>

                            <tr>
                                <td></td>
                                <td>( SD RAMJI )</td>
                            </tr>
                            @endif
                            @if($exam_type[2] != 31)
                            <tr>
                                <td>Next Term :</td>
                                <td>
                                    {{$next_semester_start_date}}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

    </div>

</body>

</html>
