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

        .user-avatar {
    border-radius: 50%;
    height: 3em;
    width: 3em;
    display: flex;
    justify-content: center;
    align-items: center;
    color: #fff;
    background: #798bff;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.06em;
    flex-shrink: 0;
    position: relative;
}

.text{
    margin-top: 7em;
}

.bg-primary {
    background-color: #007bff!important;
}

        th, td {
            border: 1px solid #dddddd !important;
            text-align: left;
            padding: 8px;
        }

        /* Zebra striping */
        tr:nth-child(even) {
            background-color: #f2f2f2 !important;
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
            border-top: 1px solid #000000;
            /*border-bottom: 1px solid #000000;*/
            border-left: 0;
            font-family: "DejaVu Sans Mono";
            width: 100%;
            border-collapse: collapse;
        }

        .table1 td {
            border-left: 1px solid #000000;
            /*border-to: 1px solid #000000;*/
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
            border-left: 1px solid #000000;
            border-bottom: 1px solid #000000;
        }

        .table1 thead th:last-child {
            border-left: 1px solid #000000;
        }

        .table1 tbody td:last-child {
            border-left: 1px solid #000000;
        }

        .table2 {
            border-top: 1px solid #000000;
            border-bottom: 1px solid #000000;
            font-family: "DejaVu Sans Mono";
            width: 100%;
            border-collapse: collapse;
        }

        .table2 td {
            border: 1px solid #000000;
            padding: 10px;
            line-height: 12px;
            font-size: 12px;
            text-align: right;
        }

        .event > span {
            position: fixed;
            top: 0px;
            right: 0px;
            display: block;
            width: 80px;
            background: #000000;

            /* Text */
            color: #fff;
            font-size: 10px;
            padding: 2px 7px;
            text-align: right;
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
    </style>
    <title>Student Lists</title>
</head>
<body>


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
        </div>
    </header>

    <hr>
<div>
     <h4 style="font-size: 15px; text-align:center; margin-top:-2px">  </h4>
    <p style="font-size: 12px; margin-top:-18px; text-align:center">  </p>
</div>


<div>
    <p></p>
</div>


<table class="table1" style="width:100%; margin-top: 10px" border="1">
    <thead>
        <tr>
            <th style="background:#069613">SN</th>
            <th style="text-align: left;font-size: 12px; background:#069613">Photo</th>
            <th style="text-align: left;font-size: 12px; background:#069613">Full Name</th>
            <th style="text-align: left;font-size: 12px; background:#069613">Class</th>
            <th style="text-align: left;font-size: 12px; background:#069613">Stream</th>
            <th style="text-align: left;font-size: 12px; background:#069613">Date Of Birth</th>
            <th style="text-align: left;font-size: 12px; background:#069613">Gender</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($students  as  $student)
        <tr>
            <td>{{ $loop->index+1 }}</td>
            <td>
                @if ($student->profile_pic)
                @php
                     $url= asset('storage/'.$student->profile_pic);
                @endphp

    <div class="user-avatar bg-primary">
    <div class="avatar-image" style="background-image: url( {{ asset($url) }});"></div>
    </div>

    @else
    <div class="user-avatar bg-primary">
     <span class="text" style="text-align: center">{{ $student->name_abbrv }}</span>
        </div>
                @endif 

            </td>
            <td>  {{ ucwords($student->full_name) }}</td>
            <td> {{ $student->getClass->name }} </td>
            <td> {{ $student->stream->name }} </td>
            <td>  {{   date("jS  F, Y", strtotime($student->dob)) }} </td>
            <td>  {{  $student->gender }} </td>

        </tr>
        @endforeach



    </tbody>



</table>

</body>
</html>
