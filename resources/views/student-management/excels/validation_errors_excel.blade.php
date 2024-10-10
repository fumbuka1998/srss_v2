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
                <th>First Name</th>
                <th>Middle Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Date Of Birth</th>
                <th>Nationality</th>
                <th>Tribe</th>
                <th>Address</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Admission Date</th>
                <th>Admission Type</th>
                <th>Religion</th>
                <th>Religion Sect</th>
                <th>House</th>
                <th>Club</th>
                <th>is Disabled</th>
                <th>Contact Person Relationship</th>
                <th>Contact Person Full Name</th>
                <th>Contact Person Occupation</th>
                <th>Contact Person Phone</th>
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
                <td>{{ $error->first_name }}</td>
                <td>{{ $error->middle_name }}</td>
                <td>{{ $error->last_name }}</td>
                <td>{{ $error->gender }}</td>
                <td>{{ $error->date_of_birth }}</td>
                <td>{{ $error->nationality }}</td>
                <td>{{ $error->tribe }}</td>
                <td>{{ $error->address }}</td>
                <td>{{ $error->phone }}</td>
                <td>{{ $error->email }}</td>
                <td>{{ $error->admission_date }}</td>
                <td>{{ $error->admission_type }}</td>
                <td>{{ $error->religion }}</td>
                <td>{{ $error->religion_sect }}</td>
                <td>{{ $error->house }}</td>
                <td>{{ $error->club }}</td>
                <td>{{ $error->is_disabled }}</td>
                <td>{{ $error->contact_person_relationship }}</td>
                <td>{{ $error->contact_person_full_name }}</td>
                <td>{{ $error->contact_person_occupation }}</td>
                <td>{{ $error->contact_person_phone }}</td>
                <td>{{ $error->validation_description }}</td>
            </tr>

            @endforeach
        </tbody>
    </table>
</body>

</html>
