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
                <span class="breadcrumb-item active mr-3">Registration</span>
            </div>
        </div>
    </div>
@endsection

@section('body')

    <style>
        .select2-container {
            min-width: 27rem;
        }

        .chosen-select-single {
            display: flex;
            flex-direction: column;
        }

        .text-color {
            color: #85898c;
        }


        .image-container {
            /* position: relative; */
            width: 200px;
            /* Adjust the size as needed */
        }

        #edit-icon {
            position: absolute;
            top: 0.625rem;
            right: 11.25rem;
            font-size: 20px;
            color: #007bff;
            cursor: pointer;
        }
    </style>

    <!--================================-->
    <!-- Basic Form Start -->
    <!--================================-->
    <div class="card mb-4 shadow-1">
        <div class="card-body collapse show" id="collapse1">
            <h4>Basic Info</h4>

            <div class="alert" role="alert">
                <span id="msg_span"></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>

                </button>
            </div>

            <form action="#" id="cform">
                <div class="row">

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-color">First Name</label>
                                    <input type="text" id="first_name" value="{{ $user ? $user->firstname : '' }}"
                                        name="firstname" class="form-control" placeholder="">
                                    <input type="hidden" name="uuid" id="uuid"
                                        value="{{ $user ? $user->uuid : '' }}">
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-color">Last Name</label>
                                    <input type="text" name="lastname" value="{{ $user ? $user->lastname : '' }}"
                                        id="lastname" class="form-control" placeholder="">
                                </div>
                            </div>
                            <input type="hidden" name="pswd" id="pswd" value="123456">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-color">Email</label>
                                    <input type="email" name="email" value="{{ $user ? $user->email : '' }}"
                                        id="email" required class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-color">Phone #</label>
                                    <input type="text" name="phone" id="phone"
                                        value="{{ $user ? $user->phone : '' }}" class="form-control" placeholder="">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-color">Address</label>
                                    <input type="text" name="address" id="address"
                                        value="{{ $user ? $user->address : '' }}" class="form-control" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-4 d-none">
                                <div class="form-group">
                                    <label class="text-color">UserName</label>
                                    <input type="text" name="username" id="user_name"
                                        value="{{ $user ? $user->username : '' }}" class="form-control" placeholder="">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div style="display: flex; align-items:center; justify-content:center;">
                            <div class="image-container">
                                <h4 class="text-center"> Profile Pic </h4>
                                
                                <div class="avatar-image" style="text-align: center;">
                                    <img style="object-fit: cover; width:7.188rem" name="profile_image" id="profile-image"
                                        src="{{ asset('assets/img/icon_avatar.jpeg') }}" alt="User Profile">
                                    <i id="edit-icon" class="fas fa-pencil-alt"></i>
                                </div>
                                <span>The Image size should not Exceed 30kb</span>
                                <p>(max-width=132,max-height=185)</p>

                                <input type="file" id="upload-image" name="file" style="display: none;">
                            </div>
                        </div>
                    </div>



                </div>
                @if (!$user)
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <h4>Assign Role</h4>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="text-color" for="">Roles</label>
                                <select name="role" class="select2s" id="roles">
                                    <option value="">Select Role...</option>
                                    @foreach ($roles as $role)
                                        @php
                                            $isSelected = false;
                                            if ($user && $user->userHasRoles) {
                                                foreach ($user->userHasRoles as $userRole) {
                                                    if ($userRole->roles->id == $role->id) {
                                                        $isSelected = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <option value="{{ $role->id }}" {{ $isSelected ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif


            </form>

            <div class="row" style="justify-content: center">
                <div class="col-md-2 mt-5">
                    <button id="sbmt-btn" class="btn btn-info btn-block mg-b-10">
                        <i class="fa fa-paper-plane mg-r-10"></i>
                        Submit
                    </button>
                </div>
            </div>

        </div>
    </div>
@section('scripts')
    <script>
        $('#sbmt-btn').click(function() {

            spark();
            let form_data = new FormData($('#cform')[0]);
            let url = '{{ route('users.management.store') }}'
            let method = "POST"
            $.ajax({
                url: url,
                processData: false,
                contentType: false,
                method: method,
                data: form_data,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(res) {
                    unspark();
                    if (res.state == 'done') {

                        clearForm($('#cform')[0]);

                        if (res.refresh) {

                            $('#address').val(res.user.address);
                            $('#first_name').val(res.user.firstname)
                            $('#lastname').val(res.user.lastname)
                            $('#phone').val(res.user.phone)
                            $('#email').val(res.user.email)

                        }

                        if ($('.alert').find('.alert-success')) {
                            $('.alert').removeClass('alert-danger');
                            $('.alert').addClass('alert-success');
                        }
                    }

                    if (res.state == 'error') {

                        if ($('.alert').find('.alert-danger')) {
                            $('.alert').removeClass('alert-success');
                            $('.alert').addClass('alert-danger');
                        }
                    }

                    $('#msg_span').text(res.msg);

                    // toast(res.msg,res.title);
                },

                error: function(res) {
                    unspark();

                    if (res.state == 'error') {
                        $('.alert').addClass('alert-success');
                        $('#msg_span').text(res.msg);


                    }

                    console.log(res)
                }
            })



        })



        $('#edit-icon').click(function() {
            $('#upload-image').click();
        });

        $('#upload-image').change(function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profile-image').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
@endsection
