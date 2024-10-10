@extends('layout.index')

@section('top-bread')
    <div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white;">
        <div class="row">
            <div class="ml-3 pd-t-5 pd-b-5 ">
                <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">USER MANAGEMENT</h1>
            </div>
            <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto ">
                <a class="breadcrumb-item" href="{{ route('dashboard') }}"><i class="icon ion-ios-home-outline"></i>
                    Dashboard</a>
                {{-- <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a> --}}
                <a class="breadcrumb-item" href="{{ route('user.management.index') }}">User Management</a>
                <span class="breadcrumb-item active mr-3">Profile</span>
            </div>
        </div>
    </div>
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

    <div class="card mt-4">

        <div class="card-body">

            <div class="row">


                @include('user-management.profile_part')

                <div class="col-md-9">
                    @include('user-management.the_nav')

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
                            padding-top: 2rem !important;
                            padding-bottom: 2rem !important;
                        }

                        .th-center {
                            padding-top: 2rem !important;
                            padding-bottom: 2rem !important;
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

                            <div class="col-md-12">
                                <table class="table responsive compact" style="width:100%">
                                    <tbody>
                                        <tr>
                                            <th class="th-color th-hover">Full Name</th>
                                            <th class="th-center th-hover full_name">
                                                {{ strtoupper($user->firstname . ' ' . $user->middlename . ' ' . $user->lastname) }}
                                            </th>
                                            <th class="th-center ripple th-hover"><i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>
                                        <tr>
                                            <th class="th-color">Email</th>
                                            <th class="th-center"> {{ $user->email }} </th>
                                            <th class="th-center"> <i style="color: #17a2b8" class="fa fa-lock"></i> </th>

                                        </tr>
                                        <tr>
                                            <th class="th-color th-hover">Phone #</th>
                                            <th class="th-center th-hover phone"> {{ $user->phone }} </th>
                                            <th class="th-center th-hover ripple"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i> </th>
                                        </tr>
                                        <tr>
                                            <th class="th-color th-hover">Date Of Birth</th>
                                            <th class="th-center th-hover"></th>
                                            <th class="th-center th-hover ripple"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
                                        </tr>
                                        <tr>
                                            <th class="th-color th-hover">Address</th>
                                            <th class="th-center th-hover address"> {{ $user->address }} </th>
                                            <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right"
                                                    style="color:#8094ae"></i></th>
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
                    <h5 class="modal-title" id="exampleModalLongTitle" style="color:#364a63">Update Profile</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="profile_basic" action="">
                        @csrf

                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="firstname">First Name </label>
                                    <input type="text" name="firstname" id="firstname" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lastname">Last Name </label>
                                    <input type="text" name="lastname" id="lastname" class="form-control">
                                </div>
                            </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address"> Address <i style="color:#069613  "
                                            class="fa-solid fa-location-dot"></i> </label>
                                    <textarea rows="3" name="address" id="address" class="form-control" placeholder="Address..."></textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone # </label>
                                    <input type="text" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="uuid" id="uuid">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Username </label>
                                    <input type="text" name="username" id="username" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password </label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="change password">
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn_update btn-info">Save changes</button>
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

            let init_url = '{{ route('users.management.registration.edit.req', ':id') }}';
            let url = init_url.replace(':id', uuid);

            $('#uuid').val(uuid)

            // users.management.registration.edit.req

            $.ajax({

                url: url,

                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(res) {
                    console.log(res.user)
                    $('#firstname').val(res.user.firstname)
                    $('#lastname').val(res.user.lastname)
                    $('#address').val(res.user.address)
                    $('#phone').val(res.user.phone)
                    $('#username').val(res.user.username)

                    console.log(res)

                },
                error: function(res) {


                    console.log(res)


                }



            })

            $('#m_modal_6').modal('show');

            unspark()



        })

        $('.btn_update').click(function() {
            spark()
            let form_data = new FormData($('#profile_basic')[0]);
            let init_url = '{{ route('users.management.profile.basic.update', ':uuid') }}';
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
                    unspark()
                    $('#m_modal_6').modal('hide');
                    $('#firstname').val(res.user.firstname)
                    $('#lastname').val(res.user.lastname)
                    $('#address').val(res.user.address)
                    $('#phone').val(res.user.phone)

                    $('.address').text(res.user.address)
                    $('.phone').text(res.user.phone)
                    let full_name = $('#firstname').val() + ' ' + $('#lastname').val();
                    $('.full_name').text(full_name)
                    toast(res.msg, res.title)

                },
                error: function(res) {
                    unspark()
                    toast(res.msg, res.title)
                }

            });

        }



        $('.edit-icon').click(function() {
            let input_file = $(this).parent().parent().find('.upload-image');
            input_file.click();
        });
    </script>
@endsection



@endsection
