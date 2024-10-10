<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validation Errors</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th style="background: #069613">Sn</th>
                <th style="background: #069613">Admission Number</th>
                <th style="background: #069613">Full Name</th>
                @foreach ($assessments as $ass )
                <th style="background: #fcd000">{{ $ass->code }}</th>
                @endforeach
                <th style="background: #fcd000; font-weight:bold">attendance</th>
                <th style="background: #fcd000; font-weight:bold">late</th>
                <th style="background: #f68749">Validation Description</th>
            </tr>
        </thead>

        <tbody>
            @foreach ( $validation_errors as $index => $error )

            return $error;

                @php
                    ++$index;
                @endphp
            <tr>
                <td>{{ $index }}</td>
                <td>{{ $error->admission_number }}</td>
                <td>{{ $error->full_name }}</td>
                @foreach ($assessments as $ass )
                @php
                    $code = $ass->code
                @endphp
                <td>{{ $error->$code }}</td>
                @endforeach
                <td>{{ $error->attendance }}</td>
                <td>{{ $error->late }}</td>
                <td>{{ $error->validation_description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
