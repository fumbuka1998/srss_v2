@extends('layout.index')

@section('top-bread')
    @include('student-management.profile_breadcrumb')
@endsection


@section('body')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css ">
    <style>
        .image-container {
            /* position: relative; */
            width: 200px;
            /* Adjust the size as needed */
        }

        .edit-icon {
            position: absolute;
            font-size: 22px;
            color: #007bff;
            cursor: pointer;
        }

        .user-profile-img img {
            border-radius: 0 !important;

        }

        .th-hover:hover {
            cursor: pointer;
        }

        .col-border {
            border-right: 1px solid #17a2b8;
            top: 0;
        }

        .col-border-top {
            border-top: 1px solid #17a2b8;
        }
    </style>

    <div class="card">
        <div class="card-body">

            <div class="row">


                @include('student-management.profile_part')

                <div class="col-md-9">
                    @include('student-management.the_nav')

                    <style>
                        .data-head {
                            padding: 0.5rem 1.25rem;
                            margin-bottom: 0.25rem;
                            background-color: #ebeef2;
                            border-radius: 4px;
                        }

                        .col-display {
                            flex-direction: column;
                        }

                        .th-color {
                            color: #8094ae;
                            padding-top: 1rem !important;
                            padding-bottom: 1rem !important;
                        }

                        .th-center {
                            padding-top: 1rem !important;
                            padding-bottom: 1rem !important;
                            color: #526484;
                        }

                        th-last:hover {

                            background: rgba(128, 148, 174, 0.3);

                        }

                        .ripple {
                            position: relative;
                            overflow: hidden;
                        }

                        /* .ripple:hover::before {
                                                content: '';
                                                position: absolute;
                                                border-radius: 50%;
                                                width: 100%;
                                                height: 100%;
                                                background: rgba(128, 148, 174, 0.3); /* Adjust the background color and opacity as needed */
                        /* animation: ripple-animation 0.6s linear; */
                        /* pointer-events: none;
                                                } */

                        @keyframes ripple-animation {
                            to {
                                transform: scale(2);
                                opacity: 0;
                            }
                        }

                        */
                    </style>

                    <div>
                        <div class="row mt-4 col-display">
                            <div class="col-md-12">
                                <div class="data-head">
                                    <h6 class="overline-title">Basics</h6>
                                </div>

                            </div>
                            <input type="hidden" name="student_uuid" id="student_uuid"
                                value="{{ $student->student_uuid }}">

                            <div class="col-md-12">
                                <table class="table responsive compact" style="width:100%">
                                    <tbody>
                                        <tr>
                                            <th class="th-color th-hover">Full Name</th>
                                            <th class="th-center th-hover">
                                                {{ $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname }}
                                            </th>
                                            <th class="th-center ripple th-hover"><i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>

                                        <tr>
                                            <th class="th-color th-hover">Date Of Birth</th>
                                            <th class="th-center th-hover dob">{{ $student->dob }}</th>
                                            <th class="th-center th-hover ripple"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>
                                        <tr>
                                            <th class="th-color th-hover">Admission Date</th>
                                            <th class="th-center th-hover registration_date">
                                                {{ $student->registration_date }} </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>
                                        <tr>
                                            <th class="th-color th-hover">Nationality</th>
                                            <th class="th-center th-hover nationality">{{ $student->nationality }} </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>
                                        <tr>
                                            <th class="th-color th-hover">Tribe</th>
                                            <th class="th-center th-hover tribe">{{ $student->tribe }} </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>
                                        {{-- <tr>
                                        <th class="th-color th-hover">Address</th> <th class="th-center th-hover address">   </th> <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right" style="color:#8094ae"></i></th>
                                        </tr> --}}
                                        {{-- <tr>
                                            <th class="th-color th-hover">Phone</th> <th class="th-center th-hover address">   </th> <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right" style="color:#8094ae"></i></th>
                                        </tr> --}}
                                        <tr>
                                            <th class="th-color th-hover">Gender</th>
                                            <th class="th-center th-hover gender"> {{ $student->gender }} </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>
                                        <tr>
                                            <th class="th-color th-hover">Admission Type</th>
                                            <th class="th-center th-hover admission_type"> {{ $student->admission_type }}
                                            </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>

                                        <tr>
                                            <th class="th-color th-hover">Religion</th>
                                            <th class="th-center th-hover religion"> {{ $religion }} </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>

                                        <tr>
                                            <th class="th-color th-hover">Religion Sect</th>
                                            <th class="th-center th-hover religion_sect"> {{ $religion_sect }} </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>

                                        {{-- <tr>
                                    <th class="th-color th-hover">House</th> <th class="th-center th-hover house">{{ $house }}   </th> <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right" style="color:#8094ae"></i></th>
                                </tr> --}}

                                        <tr>
                                            <th class="th-color th-hover">Clubs</th>
                                            <th class="th-center th-hover club">
                                                @foreach ($clubs as $club)
                                                    {{ $club['name'] }}
                                                @endforeach

                                            </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>

                                        <tr>
                                            <th class="th-color">Email</th>
                                            <th class="th-center"> {{ $student->email }} </th>
                                            <th class="th-center"> <i style="color: #17a2b8" class="fa fa-lock"></i> </th>

                                        </tr>
                                        <tr>
                                            <th class="th-color th-hover">isDisabled?</th>
                                            <th class="th-center th-hover phone"> </th>
                                            <th class="th-center th-hover ripple"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i> </th>
                                        </tr>

                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- THE EDIT MODAL --}}


    <div class="modal fade" id="m_modal_6" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style="color:#364a63">UPDATE STUDENT'S INFO</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="profile_basic" action="">
                        @csrf
                        <div class="row ml-1">
                            <h4>Basic Infos.</h4>
                        </div>
                        <hr>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="firstname">First Name </label>
                                    <input type="text" name="firstname" id="firstname" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="middlename">Middle Name </label>
                                    <input type="text" name="middlename" id="middlename" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lastname">Last Name </label>
                                    <input type="text" name="lastname" id="lastname" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tribe">Tribe </label>
                                    <input type="text" name="tribe" id="tribe" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" name="dob" id="dob" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="registration_date">Admission Date</label>
                                    <input type="date" name="registration_date" id="registration_date"
                                        class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="registration_no">Admission Number</label>
                                    <input type="number" name="registration_no" id="registration_no"
                                        class="form-control">
                                </div>
                            </div>

                            <input type="hidden" name="uuid" id="uuid">
                        </div>
                        {{-- <hr>

                        <div class="row ml-1">
                            <h4>Class & Stream Infos.</h4>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <label for="Class">Class:</label>
                                <select data-must="1" name="students_class" id="class_select" style="width: 100%"
                                    class="class_select select2s form-control form-control-sm" required>
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="streams">Stream / Combination:</label>
                                <select name="students_stream" id="stream_select" style="width: 100%"
                                    class="stream_select select2s form-control form-control-sm" required>
                                    <option value="">Select Stream</option>
                                    @foreach ($streams as $stream)
                                        <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                    </form>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn_update btn-info btn-sm">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    {{-- END EDIT MODAL --}}


@section('scripts')
    <script>


        $('.th-hover').click(function() {
            // spark()

            let uuid = @json($uuid);

            let init_url = '{{ route('students.management.registration.edit.req', ':id') }}';
            let url = init_url.replace(':id', uuid);

            $('#uuid').val(uuid)

            // students.management.registration.edit.req
            $.ajax({
                url: url,
                data: {
                    uuid: uuid
                },

                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(res) {
                    // console.log(res);
                    $('#firstname').val(res.firstname)
                    $('#lastname').val(res.lastname)
                    $('#middlename').val(res.middlename)
                    $('#dob').val(res.dob)
                    $('#registration_date').val(res.registration_date)
                    $('#tribe').val(res.tribe)
                    $('#registration_no').val(res.admission_no)
                    $('#class_select').removeClass('class_select').val(res.class_id).addClass(
                        'class_select').trigger('change');
                    $('#stream_select').removeClass('stream_select').val(res.stream_id).addClass(
                        'stream_select').trigger('change');


                },
                error: function(res) {

                    alert('an error');

                }

            })

            $('#m_modal_6').modal('show');
            unspark()
        })

        $('.btn_update').click(function() {
            spark()
            let form_data = new FormData($('#profile_basic')[0]);
            let init_url = '{{ route('students.management.profile.basic.update', ':uuid') }}';
            let url = init_url.replace(':uuid', @json($uuid))
            updateProfileBasic(form_data, url);

        })

        function updateProfileBasic(form_data, url) {
            $.ajax({
                processData: false,
                contentType: false,
                method: 'POST',
                url: url,
                data: form_data,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(res) {
                    console.log(res);

                    unspark()
                    $('#m_modal_6').modal('hide');
                    $('#firstname').val(res.firstname)
                    $('#lastname').val(res.lastname)
                    $('#middlename').val(res.middlename)
                    $('#dob').val(res.dob)
                    $('#tribe').val(res.tribe)
                    $('#registration_date').val(res.registration_date)

                    $('.dob').text(res.dob)
                    $('.nationality').text(res.nationality)
                    $('.registration_date').text(res.registration_date)
                    $('.tribe').text(res.tribe)
                    let full_name = $('#firstname').val() + ' ' + $('#lastname').val();
                    $('.full_name').text(full_name)

                    toast(res.msg, res.title)

                    location.reload();

                },
                error: function(res) {
                    unspark()
                    toast(res.msg, res.title)
                }

            });

        }


        // $('.edit-icon-og').click(function() {
        //     let input_file = $(this).parent().parent().find('.upload-image');
        //     input_file.click();
        // });

        $('.edit-icon').click(function() {
            let input_file = $(this).parent().parent().find('.upload-image');

            input_file.change(function() {
                const file = this.files[0];

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile-image').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(file);

                    $('.remove-profile').removeClass('d-none');
                }
            });
            input_file.click();
        });

        let student_uuid = $('#student_uuid').val();



        $('.upload-image').change(function() {

            var formData = new FormData();
            formData.append('file', $(this)[0].files[0]);

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            let url = '{{ route('students.image.update', [':uuid']) }}';
            url = url.replace(':uuid', student_uuid);

            $.ajax({
                url: url,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function(res) {
                    // console.log(res.msg);
                    if (res.state === "Done") {
                        // console.log(res.title);
                        alert(res.msg);
                        toast(res.msg, res.title)
                    }
                },
                error: function(xhr, status, error) {
                    // alert("am getting an error");
                    console.error(xhr.responseText);
                }
            });
        });


        $('.remove-profile').click(function() {
            $('#profile-image').attr('src', '{{ asset('assets/img/icon_avatar.jpeg') }}');
            $(this).addClass('d-none')
        });

        $('#class_select').change(function() {
            let class_id = $(this).val()
            spark()
            $.ajax(

                {
                    url: '{{ route('academic.class.streams.fetch') }}',
                    method: "POST",
                    data: {
                        id: class_id
                    },

                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                    success: function(res) {
                        unspark()
                        $('#stream_select').html(res)

                    },
                    error: function(res) {
                        unspark()
                        console.log(res)
                    }

                })




        })

        function checkEmptyFields() {
            let isEmpty = false;
            $('.form-control').each(function() {
                if ($(this).prop('required') && !$(this).val()) {
                    isEmpty = true;
                    return false;
                }
            });
            return isEmpty;
        }

        // Initially disable the "Save changes" button if any required fields are empty
        if (checkEmptyFields()) {
            $('.btn_update').prop('disabled', true);
        }

        // Check for empty fields whenever an input field value changes
        $('.form-control').on('input', function() {
            if (checkEmptyFields()) {
                $('.btn_update').prop('disabled', true);
            } else {
                $('.btn_update').prop('disabled', false);
            }
        });


    </script>
@endsection



@endsection
