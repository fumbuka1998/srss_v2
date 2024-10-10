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
            font-size: 10px;
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
    </style>
    <title>Results</title>
</head>
<body>


<header class="clearfix">

    <div id="logo">

        <img src="{{ asset('assets/logo/sbrt_logo.gif') }}" alt="">

        <address class="address" style="line-height: 16px">
            <h2 style="color: #040404">Shaaban Robert Secondary School</h2>
            <strong style="color: #000000">P.0 BOX 736, DAR ES SALAAM TANZANIA</strong> <br>
            <strong style="color: #000000">Tel: +(255) 22 2114903 email:info@shaabanrobert.sc.tz</strong>  <br/><strong>
        </address>
    </div>



</header>
<div>
    <p style="font-size: 16px; text-align:center"> {{ $examInfo->name  }} RESULTS </p>
    <p style="font-size: 12px; margin-top:-18px; text-align:center"> SEMESTER </p>
</div>
<div>

<div class="aaa">
    <p>NAME:  {{ $studentInfo->firstname .' '. $studentInfo->middlename. ' '.$studentInfo->lastname  }}</p>
    <p>CLASS: {{ $studentInfo->class_name .' '. $studentInfo->stream_name    }}  </p>
</div>

<p>YEAR: </p>

</div>

<table class="table1" style="width:100%; margin-top: 10px" border="1">
    <thead>
        <tr>
            <th style="text-align: left;font-size: 12px">ADMISSION NO.</th>
            <th>FULL NAME</th>
            <th style="text-align: left;font-size: 12px">MARKS/{{$examInfo->total_marks}}</th>
            <th style="text-align: left;font-size: 12px">%</th>
            <th style="text-align: left;font-size: 12px">GRADE</th>
            <th style="text-align: left;font-size: 12px">REMARKS</th>
        </tr>
    </thead>

    <tbody>

        @php
        $helper = new GlobalHelpers();
        @endphp

        @foreach ($subjects as $index => $subjct )
        <tr>
            <td>{{ $subjct->name }}</td>

            @php



            $score = '-';
            $percentage = '-';

            foreach ($results as $result )

            if ($result->subject_id == $subjct->id) {

                $score = $result->score;
                $percentage = ($score/$examInfo->total_marks) * 100;

                $processedValue = $helper->getGrade($score,$elevel,$examInfo);



            }


            if ($processedValue['grade'] == 'D') {
                $bgcolor = '#f9a11c';
            }elseif ($processedValue['grade'] == 'F') {
                $bgcolor = '#e36e60';
            }
            else{
                $bgcolor = 'inherit';
            }

            @endphp
            <td> {{  $score  }} </td>
            <td> {{ round($percentage) }} </td>
            <td style="background-color: {{  $bgcolor }}">{{ $processedValue['grade']}}</td>

            <td><span>{{ $processedValue['remarks']}}</span> </td>


        </tr>
        @endforeach

        <tr>
            <td>Total No. Of Subjects Sat</td>
            <td colspan="4">30</td>
        </tr>

        <tr>
            <td>Total No. Of Subjects Passed</td>
            <td colspan="4"></td>
        </tr>

        <tr>
            <td>Points</td>
            <td colspan="4"></td>
        </tr>

        <tr>
            <td>Division</td>
            <td colspan="4"></td>
        </tr>

        <tr>
            <td>Attendance (x/y)</td>
            <td colspan="4"></td>
        </tr>

        <tr>
            <td>Number of Days Late (d/y)</td>
            <td colspan="4"></td>
        </tr>

        <tr>
            <td style="height:40px">Remarks</td>
            <td style="height:40px" colspan="4"></td>

        </tr>

        <tr>
            <td>CT Signature:</td>
            <td colspan="4">Date:</td>
        </tr>

        <tr>
            <td> HM Signature: </td>
            <td colspan="4"></td>
        </tr>



    </tbody>



</table>

</body>
</html>
