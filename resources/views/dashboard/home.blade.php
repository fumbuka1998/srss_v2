@extends('layout.index')

@section('body')

    <style>
        .select2-container {
            min-width: 27rem;
        }

        .chosen-select-single {
            display: flex;
            flex-direction: column;
        }

        .description {

            padding-top: 1rem;
            font-size: 1.2rem;
            color: #30517a;
            font-weight: 500;

        }

        .description:hover {

            color: #36a000;
        }


        .income-dashone-pro {

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 12rem;
            max-height: 12rem;

        }

        .appear_right {
            /* animation: slideInLeft 1s ease-out; */
            animation: slideInRight 1s ease-out;
        }

        .appear_left {
            animation: slideInLeft 1s ease-out;
        }

        .img {

            padding-top: 2rem;

        }

        .card {
            animation: slideInRight 1s ease-out;
            /* Animation for cards appearing from right */
        }

        @keyframes slideInLeft {
            0% {
                transform: translateX(-100%);
                opacity: 0;
            }

            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            0% {
                transform: translateX(100%);
                opacity: 0;
            }

            100% {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .parent-flex{
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;

        }
        .child-flex{
            width:12rem;
            margin-top:2rem;
            margin-bottom:2rem;
        }
        .child-flex-graph{
            width:38rem;
            margin-top:2rem;
            margin-bottom:2rem;
        }


       

        /* Media query for smaller screens */
@media screen and (max-width: 768px) {
    .child-flex,.child-flex-graph {
        width: calc(50% - 2rem); /* Display two items per row, with some margin */
    }
}

/* Media query for even smaller screens */
@media screen and (max-width: 480px) {
    .child-flex,.child-flex-graph {
        width: calc(100% - 2rem); /* Display one item per row, with some margin */
    }
}


        /* #chartdiv {
                width: 100%;
                height: 500px;
            } */
    </style>


    <div class="container-fluid">

        {{-- by flex box --}}

        <div class="parent-flex">
            <div class="child-flex"  style="background-color: white">

                <a href="{{ route('students.ongoing') }}">
                    <div class="income-dashone-pro">
                        <img class="img" src="{{ asset('assets/img/ico_student_das.svg') }}" alt="">
                        <p class="description">Students</p>
                    </div>
                </a>


            </div>

            <div class="child-flex" style="background-color: white">
                <a href="#">
                    <div class="income-dashone-pro">
                        <img class="img" src="{{ asset('assets/img/ico_teacher.svg') }}" alt="">
                        <p class="description">Teachers</p>
                    </div>
                </a>

            </div>

            <div class="child-flex" style="background-color: white">
                <a href="#">
                    <div class="income-dashone-pro">
                        <img class="img" src="{{ asset('assets/img/ico_attendance.svg') }}" alt="">
                        <p class="description">Attendance</p>
                    </div>
                </a>

            </div>

            <div class="child-flex"  style="background-color: white">

                <a href="#">
                    <div class="income-dashone-pro">
                        <img class="img" src="{{ asset('assets/img/ico_class.svg') }}" alt="">
                        <p class="description"> Classes </p>
                    </div>
                </a>



            </div>

            <div class="child-flex" style="background-color: white">

                <a href="#">
                    <div class="income-dashone-pro">
                        <img class="img" src="{{ asset('assets/img/ico_class.svg') }}" alt="">
                        <p class="description"> Marking </p>
                    </div>
                </a>



            </div>
            <div class="child-flex" style="background-color: white">

                <a href="#">
                    <div class="income-dashone-pro">
                        <img class="img" src="{{ asset('assets/img/ico_class.svg') }}" alt="">
                        <p class="description"> Reports </p>
                    </div>
                </a>



            </div>

        </div>


        <div class="parent-flex" >
            <div class="child-flex-graph" style="background-color: white">
                <div class="card-header" style="background-color: #069614; color:white">
                    <p>Bar chart to show the number of student in every class</p>
                </div>
                <div class="card-body">
                    <canvas id="studentsByClass" width="500" height="250"></canvas>
                </div>
            </div>
            <div class="child-flex-graph" style="background-color: white">
                <div class="card-header" style="background-color: #069614; color:white">
                    <p>pie chart to show the male and female number distribution</p>
                </div>
                <div class="card-body">
                    <canvas id="genderChart" width="500" height="250"></canvas>
                </div>
            </div>




        </div>


    </div>


@section('scripts')
    <script>
        var ctx1 = document.getElementById('studentsByClass').getContext('2d');
        let test_data = {!! $studentsByClassName->values() !!};
        console.log(test_data);
        var chart1 = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: {!! $studentsByClassName->keys() !!},
                datasets: [{
                    label: 'number of students',
                    data: {!! $studentsByClassName->values() !!}
                }]
            },
            options: {
                responsive: true,

            }
        });

        var ctx1 = document.getElementById('genderChart').getContext('2d');
        var chart1 = new Chart(ctx1, {
            type: 'pie',
            data: {
                labels: ['male', 'Female'],
                datasets: [{
                    label: 'Student gender Distribution',
                    data: {!! json_encode(array_values($genderCount)) !!}
                }]
            },
            options: {
                responsive: true,

            }
        });
    </script>
@endsection




@endsection
