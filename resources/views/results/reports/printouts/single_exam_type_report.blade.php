<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        body {
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

        .aaa{
            display: flex;
            align-items: center;
        }

        #cover_page {
    position: absolute;
    left: 89px;
    top: 450px;
    letter-spacing: -0.34px;
    z-index: -3;
    opacity: 0.1; /* Set a negative z-index to place it behind other elements */
}

    </style>
    <title>Results</title>
</head>
<body>
    <span id="cover_page" style="position: absolute; align-items:center">
        <img height="350" width="500" src="{{ asset('assets/logo/sbrt_logo.gif') }}" alt="">
        </span>

<header class="clearfix">

    <div id="logo" >
        <div style="float: left;">
            <img src="{{ asset('assets/logo/sbrt_logo.gif') }}" alt="">
        </div>
        <div class="address" style="float: left; margin-left: 20px; line-height: 16px;">
            <h2 style="color: #040404; margin-bottom:-4px; letter-spacing: 2px;">SHAABAN ROBERT SECONDARY SCHOOL</h2>
            <span style="color: #000000; font-size: 11.5px">P.0 BOX 736, DAR ES SALAAM TANZANIA</span> <br>
            <span style="color: #000000; font-size: 11.5px">Tel: +(255) 22 2114903, (+255) 22 21 14 935</span> <br/>
            <span style="font-size: 11px"> email:info@shaabanrobert.sc.tz &nbsp; WEBSITE: www.shaabanrobert.sc.tz </span>
        </div>

        <div style="float: right;">

            @if ($profile_pic)

                <div
                style="
                width: 6rem;
                height: 6rem;
                border-radius: 50%;
                overflow: hidden;
                background-image: url('{{ asset('storage/'.$profile_pic) }}');
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
                "
                >
                <img
                style="
                width: 100%;
                height: auto;
                " src="{{ asset('assets/img/icon_avatar.jpeg') }}" alt="Profile Picture">
                </div>

            @endif
        </div>
    </div>
</header>
<hr>
<div>
     <h4 style="font-size: 15px; text-align:center; margin-top:-2px">{{ strtoupper($exam_report->name)   }}  RESULTS</h4>
    <p style="font-size: 12px; margin-top:-18px; text-align:center"> {{ strtoupper($semester->name)  }} </p>
</div>
<div>



<div class="aaa">
<table>
    <tr>
        <td>NAME:</td>
        <td style="min-width:200px; max-width:250px;"> <strong> <i> {{ strtoupper($results->full_name)   }} </i> </strong> </td>

        <td style="padding-left:40px">CLASS:</td>
        <td> <strong>{{  strtoupper($school_class->name .' '. $stream->name )   }}</strong> </td>

        <td style="padding-left:40px"> REGN: </td>
        <td> <strong> <i>  {{ $reg_no }}  </i>   </strong> </td>

        <td style="float: right">YEAR: </td>
        <td>  <strong> <i> {{ $year->name }} </i> </strong>  </td>
    </tr>
</table>
</div>
</div>

<table class="table1" style="width:100%; margin-top: 10px" border="1">
    <thead>
        <tr>
            <th style="text-align: left;font-size: 11px">SUBJECTS</th>
            <th style="text-align: left;font-size: 11px">MARKS/{{ $exam->total_marks }} </th>
            <th style="text-align: left;font-size: 11px">%</th>
            <th style="text-align: left;font-size: 11px">GRADE</th>
            <th style="text-align: left;font-size: 11px">REMARKS</th>
        </tr>
    </thead>

    <tbody>

        @php
        $helper = new GlobalHelpers();
        @endphp

        @foreach ( $metadata->results as $index => $result )
        {{--
        @php
            $passmarks = json_decode($results->metadata)->passmarks;

        @endphp --}}
        <tr>


            <td>{{ $result->name }}</td>
            <td>{{ $result->score }} </td>
            <td>{{ $result->percent }}</td>
            <td>{{ $result->grade  }}</td>
            <td> {{ $result->remarks }} </td>

            @php

            $score = '-';
            $percentage = '-';
            $processedValue = 'F';
            $processedValue = 'FAIL';

            foreach ($results as $result )

            if (1 ) {



            }

            if (1 == 'D') {
                $bgcolor = '#f9a11c';
            }elseif (1 == 'F') {
                $bgcolor = '#e36e60';
            }
            else{
                $bgcolor = 'inherit';
            }

            @endphp
        </tr>
        @endforeach

        {{-- <tr>
            <td>Total No. Of Subjects Sat</td>
            <td colspan="4">30</td>
        </tr>

        <tr>
            <td>Total No. Of Subjects Passed</td>
            <td colspan="4"></td>
        </tr> --}}

        <tr>
            <td>Division</td>
            <td colspan="4">{{ $results->division }}</td>
        </tr>

        <tr>
            <td>Points</td>
            <td colspan="4">   {{ $results->points }} </td>
        </tr>



        {{-- <tr>
            <td>Attendance (x/y)</td>
            <td colspan="4"></td>
        </tr>

        <tr>
            <td>Number of Days Late (d/y)</td>
            <td colspan="4"></td>
        </tr> --}}

        {{-- <tr>
            <td>
                <div style="display:flex; justify-content:space-between; align-items:center">
                     <p style="">CT Signature:</p> &nbsp; <img style="height:40px;"  src="{{ asset('assets/signature/teacher.png') }}" alt="">   <span> {{ date('jS F Y')  }} </span>  </div>
                    </td>
            <td colspan="4"> CT'S Comment: &nbsp; <span> {{ $results->ct_comments }} </span></td>
        </tr>

        @if ($is_signature)
        <tr>
           <td>
            <div style="display:flex; justify-content:space-between; align-items:center">
            <p style="">HM Signature:</p> &nbsp; <img style="height:40px;"  src="{{ asset('assets/signature/hm_signature-removebg-preview.png') }}" alt="">
            <span> {{ date('jS F Y')  }} </span>
            </div>
        </td>
            <td colspan="4">HM'S Comment: &nbsp; <span> {{ $results->hm_comments }} </span> </td>
        </tr>

        @endif --}}

        <tr>
            <td>Class Teacher's Comment</td>
            <td colspan="4">{{ $results->ct_comments }}</td>
        </tr>

        <tr>
            <td rowspan="2">Class Teacher's Name</td>
            <td  colspan="2" rowspan="2"> {{ $class_teacher->full_name }} </td>
            <td>Signature</td>
            <td colspan="1"></td>

        </tr>

        <tr>
            <td>Date</td>
            {{-- <td colspan="1"><span> {{ date('jS F Y')  }} </span>></td> --}}
            <td colspan="1"></td>

        </tr>

        <tr>
            <td rowspan="2">Parents Name</td>
            <td  colspan="2" rowspan="2"></td>
            <td>Signature</td>
            <td colspan="1"></td>
        </tr>

        <tr>
            <td>Date</td>
            <td colspan="1"></td>
        </tr>

    </tbody>



</table>

</body>
</html>
