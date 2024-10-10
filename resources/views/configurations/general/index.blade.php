@extends('layout.index')

@section('top-bread')
    <div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white;">
        <div class="row">
            <div class="ml-3 pd-t-5 pd-b-5 pl-3">
                <h1 class="pd-0 mg-0 tx-20 text-overflow new-header">THE SCHOOL PROFILE</h1>
            </div>
            <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto pr-4">
                <a class="breadcrumb-item" href="{{ route('dashboard') }}"><i class="icon ion-ios-home-outline"></i>
                    Dashboard</a>
                <span class="breadcrumb-item active">School Profile</span>
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

        .edit-icon {
            font-size: 24px;
            color: #333;
            cursor: pointer;
        }
    </style>

    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="contact-client-img" style="display: flex; align-items:center; flex-direction:column">
                        {{-- <a href="#"><img style="max-width: 30rem;" src="{{ asset('assets/logo/sbrt_logo.gif') }}" alt="">
                    </a> --}}
                        <input type="hidden" id="school_id" name="school_id" value="{{ $profile->id }}">

                        @if ($imageUrl)
                            <div class="image-container" style=" min-width: 10rem; max-width: 20rem;">
                                <div>
                                    <img style="object-fit: cover; width: 200px" name="school_logo" id="school_logo"
                                        src=" {{ $imageUrl }} " alt="School Logo">
                                    <i class="edit-icon fas fa-pencil-alt"> <span class="badge badge-primary"></span></i>
                                </div>

                                <div class="form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" class="upload-image" name="file" style="display: none;">
                                    <div class="col-md-12 mt-4 text-center remove-profile d-none">
                                        <a href="javascript:void(0)" title="remove picture" class="btn btn-danger btn-sm">
                                            <i class="fa fa-remove"></i> </a>
                                    </div>
                                </div>

                            </div>
                        @else
                            <div class="image-container" style=" min-width: 20rem; max-width: 20rem;">
                                <div>
                                    <img style="object-fit: cover; width: 200px" name="school_logo" id="profile-image"
                                        src="{{ asset('assets/logo/default_logo.png') }}" alt="school Profile">
                                    <i class="edit-icon fas fa-pencil-alt"></i>
                                </div>

                                <form enctype="multipart/form-data" class="form">
                                    @csrf
                                    <input type="file" class="upload-image" name="file" style="display: none;">
                                </form>

                            </div>
                        @endif








                    </div>
                    <div class="text-center">
                        <button id="edit_btn" class="btn btn-info"><i style="font-size: 1.5rem;" class="fa fa-edit"> </i>
                            edit</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact-client-content">
                        <h2 data-non-input><a href="#" id="school_name">{{ $profile->school_name }}</a></h2>
                        <input value="{{ $profile->school_name }}" placeholder="school name" type="text" class="form-control d-none"
                            id="school_name_input" data-field="school_name">
                        <p data-non-input id="location"><i class="fa fa-map-marker"></i> {{ $profile->location }}</p>
                        <br>
                        <input value="{{ $profile->location }}" placeholder="location" type="text" class="form-control d-none"
                            id="location_input" data-field="location"><br>

                    </div>
                    <div class="contact-client-address">
                        <h3 data-non-input>Education, Inc.</h3>
                        <p data-non-input id="address" class="address-client-ct"><strong>School Address:</strong>
                            {{ $profile->address }}</p>
                        <input value="{{ $profile->address }}" placeholder="address" type="text" class="form-control d-none" id="address_input"
                            data-field="address"><br>
                        <p data-non-input id="phone"><strong>School Phone:</strong> {{ $profile->phone }}</p><br>
                        <input value="{{ $profile->phone }}" placeholder="phone" type="text" class="form-control d-none" id="phone_input"
                            data-field="phone"><br>
                        <p data-non-input id="email"><strong>School Email:</strong> {{ $profile->email }}</p><br>
                        <input value="{{ $profile->email }}" placeholder="myemail@gmail.com" type="text" class="form-control d-none" id="email_input"
                            data-field="email">
                    </div>
                    <br>
                    <div style="float:right">
                        <button id="save_btn" class="btn btn-info btn-sm"><i style="font-size: 1.5rem;" class="fa fa-save">
                            </i> save</button>
                    </div>

                </div>

            </div>


        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="tab-content">
                <div id="inbox" class="tab-pane fade in animated zoomInDown custom-inbox-message shadow-reset active">
                    <div class="mail-title inbox-bt-mg">
                        <h2>School Profile </h2>
                        <div class="view-mail-action view-mail-ov-d-n">
                            <a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> </a>
                        </div>
                    </div>
                    <div>


                    </div>
                </div>
            </div>
        </div>
    </div>





    {{-- end --}}


@section('scripts')
    <script>
        $(document).ready(function() {
            // Hide
            $('[data-field]').addClass('d-none');
            $('#save_btn').addClass('d-none');

            $('#edit_btn').click(function() {
                // show input fields and save button
                $('[data-field]').toggleClass('d-none');
                $('#save_btn').toggleClass('d-none');

                // show non-input elements
                $('[data-non-input]').toggleClass('d-none');
            });

            $('#save_btn').click(function() {
                var data = {};

                $('[data-field]').each(function() {
                    var field = $(this).data('field');
                    var value = $(this).val();
                    data[field] = value;
                });



                $.ajax({
                    url: '{{ route('configuration.general.profile') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        data: data
                    },
                    success: function(response) {
                        $('[data-field]').toggleClass('d-none');
                        $('#save_btn').toggleClass('d-none');
                        $('#edit_btn').removeClass('d-none');

                        $('[data-non-input]').toggleClass('d-none');
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                        // location.reload();

                        // Update the DOM with saved data
                        $('#school_name').text(response.data.school_name);
                        $('#location').text(response.data.location);
                        $('#address').text(response.data.address);
                        $('#phone').text('Phone: ' + response.data.phone);
                        $('#email').text('email: ' + response.data.email);
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred: ' + error,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });




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

        let school_id = $('#school_id').val();



        $('.upload-image').change(function() {

            // console.log(school_id);

            var formData = new FormData();
            formData.append('file', $(this)[0].files[0]);

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            let url = '{{ route('school.image.update', [':id']) }}';
            url = url.replace(':id', school_id);

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
                        alert(res.msg);
                        toast(res.msg, res.title)
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });


        $('.remove-profile').click(function() {
            $('#profile-image').attr('src', '{{ asset('assets/img/icon_avatar.jpeg') }}');
            $(this).addClass('d-none')
        });
    </script>
@endsection
@endsection
