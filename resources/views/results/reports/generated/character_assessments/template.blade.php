<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Character assessments</title>
</head>
<body>

<table>
    <thead>
    <tr>
        <th style="background:#069613; color:white; font-weight:bold">Admission Number</th>
        <th style="background:#069613; color:white; font-weight:bold">Full Name</th>
        @foreach ($assessments as $ass )
        <th style="background: #fcd000;">{{ $ass->code }}</th>
        @endforeach
        <th style="background: #fcd000; font-weight:bold">Attendance</th>
        <th style="background: #fcd000; font-weight:bold">Late</th>
    </tr>
    </thead>

    <tbody>

        @foreach ($students as $student )

        <tr>
             <td>{{ $student->id }} </td>
            <td>{{   $student->firstname .' '.$student->middlename. ' '.$student->lastname   }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>


        @endforeach


    </tbody>

</table>

</body>
</html>

