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
                <th>Sn</th>
                <th>Admission Number</th>
                <th>Full Name</th>
                <th>Score</th>
                <th>Validation Description</th>
            </tr>
        </thead>
        <tbody>

            @foreach ( $validation_errors as $index => $error )

                @php
                    ++$index;
                @endphp
            <tr>
                <td>{{ $index }}</td>
                <td>{{ $error->admission_number }}</td>
                <td>{{ $error->full_name }}</td>
                <td>{{ $error->score }}</td>
                <td>{{ $error->validation_description }}</td>
            </tr>

            @endforeach
        </tbody>
    </table>
</body>

</html>
