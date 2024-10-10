<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Students Registration</title>
</head>
<body>

<table>
    <thead>
    <tr>
        <th>Admission Number</th>
        <th>Full Name</th>
        <th>Score</th>
    </tr>
    </thead>

    <tbody>

        @foreach ($students as $student )

        <tr>
            <td>{{ $student->id }} </td>
            <td>{{   $student->firstname .' '.$student->middlename. ' '.$student->lastname   }}</td>

        </tr>


        @endforeach


    </tbody>

</table>

</body>
</html>

