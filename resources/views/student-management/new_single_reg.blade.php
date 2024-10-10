@extends('layout.index')


@section('body')

    <style>
        .wizard>.content {
            background: #ebf2f7;
            overflow: auto;
            /* min-height: 40em; */
        }

        .wizard>.steps .disabled a {
            background: #ebf2f7;
        }

        .wizard>.content>.body {
            width: 100%;

        }


        .custom-control:hover {
            cursor: pointer;
        }

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

    <div class="card mb-4 shadow-1">
        <div class="card-header">
            <h4 class="card-header-title">
                STUDENT REGISTRATION
            </h4>
        </div>
        <div class="card-body">

            <div class="row mb-3 ml-2">
                <div class="col-md-4 mg-t-20 mg-lg-t-0">
                    <div class="custom-control custom-radio">
                        <input type="radio" {{ $activeRadio == 'single' ? 'checked' : '' }} value="single" name="a"
                            class="custom-control-input" id="radio2">
                        <label class="custom-control-label" for="radio2">Single Registration</label>
                    </div>
                </div>
                <div class="col-md-4 mg-t-20 mg-lg-t-0">
                    <div class="custom-control custom-radio">
                        <input type="radio" value="multiple" {{ $activeRadio == 'multiple' ? 'checked' : '' }}
                            name="a" class="custom-control-input" id="radio3">
                        <label class="custom-control-label" for="radio3">Multiple Registration</label>
                    </div>
                </div>

            </div>


            <form id="form-submit" enctype="multipart/form-data">

                <div id="wizard2" class="parent-container">
                    <h3>Personal Information</h3>
                    <section>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Admission Number: <span class="required tx-danger">*</span> <span
                                            class="required"></span> </label>
                                    <input type="text" value="" name="admission_number" data-success="0"
                                        class="form-control invalid form-control-sm" id="admission_number" required>
                                    <div class="badge" id="loader"></div>
                                </div>

                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="First Name">First Name: <span class="required tx-danger">*</span> </label>
                                    <input data-must="1" type="text" name="first_name"
                                        class="form-control form-control-sm" id="firstname" required>
                                    <input type="hidden" name="stdnt_id" id="stdnt_id"
                                        class="form-control form-control-sm">

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Middle Name">Middle Name: </label>
                                    <input type="text" name="middle_name" class="form-control form-control-sm"
                                        id="middlename">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Last Name">Last Name: <span class="required tx-danger">*</span> <span
                                            class="required"></span></label>
                                    <input data-must="1" type="text" name="last_name"
                                        class="form-control form-control-sm" id="lastname" required>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Admission Date <span class="required tx-danger">*</span></label>
                                    <input type="date" data-must="1" name="admitted_year" id="admitted_year"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>

                            <div class="col-md-3">

                                <label>Nationality <span class="required tx-danger">*</span></label>

                                <select name="nationality" id="nationality" class="select2s form-control" required>
                                    <option>Select</option>
                                    @foreach ($nationalities as $nationality)
                                        <option value="{{ $nationality->name }}">{{ $nationality->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tribe">Tribe:</label>
                                    <input type="text" name="tribe" class="form-control form-control-sm"
                                        id="tribe">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Address">Address:</label>
                                    <input type="text" name="student_address" id="std_address"
                                        class="form-control form-control-sm">
                                    <input type="hidden" name="std_address_id" id="std_address_id">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Phone">Phone:</label>
                                    <input type="text" name="student_phone" id="std_phone"
                                        class="form-control form-control-sm">
                                    <input type="hidden" name="std_contact_id" id="std_contact_id"
                                        class="form-control form-control-sm" id="phone">
                                </div>
                                <input type="hidden" name="account_id" id="account_id">
                                <input type="hidden" name="std_phone_id" id="std_phone_id">
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Email">Email: </label>
                                    <input type="email" name="student_email" id="std_email"
                                        class="form-control form-control-sm">
                                    <span class="text-danger" id="email_error"></span>
                                    <input type="hidden" name="std_email_id" id="std_email_id">

                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Date of Birth">Date of Birth: <span class="tx-danger">*</span> <span
                                            class="required"></span></label>
                                    <input data-must="1" type="date" min="{{ date('y-m-d') }}" name="dob"
                                        class="form-control form-control-sm" id="dob" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="Gender">Gender: <span class=" tx-danger">*</span> <span
                                            class="required"></span></label>
                                    <select data-must="1" name="gender" id="std_gender"
                                        class="form-control select2s form-control-sm">
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Admission Type <span class="required tx-danger">*</span> <span
                                            class="required"></span></label>
                                    <select data-must="1" name="admission_type" id="admission_type"
                                        class="form-control select2s form-control-sm class_select" required>
                                        <option value=""></option>
                                        <option value="started">Started</option>
                                        <option value="transfered">Transfered</option>
                                        <option value="continuing">Continuing</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label for="">Religion <span class="required tx-danger">*</span> <span
                                            class="required"></span></label>
                                    <select name="religion" id="religion"
                                        class="select2s form-control  select2s form-control-sm" required>
                                        <option value=""></option>
                                        @foreach ($religions as $region)
                                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>


                            <div id="rel_sect" style="display: none" class="col-md-3">
                                <div class="form-group">
                                    <label for="">Religion Sect</label>
                                    <select style="width: 100%" name="religion_sect" id="religion_sect"
                                        class="form-control select2s  form-control-sm">
                                    </select>
                                </div>

                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">House <span class="required tx-danger">*</span> <span
                                            class="required"></span></label>
                                    <select style="width: 100%" name="house" id="house"
                                        class="form-control select2s form-control-sm">
                                        <option value=""></option>
                                        @foreach ($houses as $house)
                                            <option value="{{ $house->id }}">{{ $house->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">

                                <div class="form-group">
                                    <label for="">Clubs <span class="required tx-danger">*</span> <span
                                            class="required"></span></label>
                                    <select name="club" id="clubs"
                                        class="select2_demo_3 form-control select2s form-control-sm">
                                        <option value=""></option>
                                        @foreach ($clubs as $club)
                                            <option value="{{ $club->id }}">{{ $club->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="col-md-3 mg-t-20 mg-lg-t-0" style="margin-top: 2em">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" value="1"
                                        name="is_disabled" id="customCheck2">
                                    <label class="custom-control-label" for="customCheck2">is Disabled</label>
                                </div>
                            </div>



                    </section>

                    <h3>Contact Person Details</h3>
                    <section>

                        <div class="loader_gif d-none">
                            <img style="max-width:15%" src="{{ asset('assets/images/cupertino_loader.gif') }}"
                                alt="">
                        </div>
                        <div id="contact_person_details_div">
                            <div class="contact_people">
                                <div class="row contact_people_row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="relationship">Relationship:</label>
                                            <select name="contact_person_relationship[]" style="width: 100%"
                                                class="form-control select2s relationship considerable form-control-sm">
                                                <option value="">Select</option>
                                                <option value="FATHER">FATHER</option>
                                                <option value="MOTHER">MOTHER</option>
                                                <option value="GUARDIAN">GUARDIAN</option>
                                                <option value="WIFE">WIFE</option>
                                                <option value="HUSBAND">HUSBAND</option>
                                                <option value="FRIEND">FRIEND</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Name">Name:</label>
                                            <input name="contact_person_name[]"
                                                class="form-control contact_person_name form-control-sm" />
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="Occupation">Occupation:</label>
                                            <input name="contact_person_occupation[]"
                                                class="form-control occupation form-control-sm" />
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="Phone">Phone:</label>
                                            <input type="number" name="contact_person_phone[]"
                                                class="form-control phone form-control-sm" />
                                        </div>
                                    </div>
                                    <div class="col-md-2" style="display: flex; align-items: center; margin-top: 0.5em;">
                                        <a class="add_button_img" href="javascript:void(0)"> <i
                                                class="fa fa-plus-circle"></i> <span>Add</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>


                    <h3>Class & Stream Information</h3>

                    <section>
                        <div>
                            <div class="loader_gif d-none">
                                <img style="max-width:15%" src="{{ asset('assets/images/cupertino_loader.gif') }}"
                                    alt="">
                            </div>

                            <div class="row" id="form-3_div">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="Class">Class:</label>
                                        <select data-must="1" name="students_class" id="class_select"
                                            style="width: 100%" class="class_select select2s form-control form-control-sm"
                                            required>
                                            <option value="">Select Class</option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="streams">Stream / Combination:</label>
                                        <select name="students_stream" id="stream_select" style="width: 100%"
                                            class="class_select form-control select2s form-control-sm" required>
                                            <option value="">Select Stream</option>
                                            @foreach ($streams as $stream)
                                                <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>



                    <h3>Attachments</h3>

                    <section>

                        <div>

                            <div class="col-md-3 col-border text-center">
                                <h6 class="text-center">Upload Profile Picture</h6>
                                <span>The Image size should not Exceed 30kb</span>
                                <p>(max-width=132,max-height=185)</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="user-profile-img text-center">
                                            <div style="display: flex; align-items:center; flex-direction:column">
                                                <div class="image-container" style=" min-width: 20rem; max-width: 20rem;">
                                                    <div>
                                                        <img style="object-fit: cover; width: 200px" name="profile_image"
                                                            id="profile-image"
                                                            src="{{ asset('assets/img/icon_avatar.jpeg') }}"
                                                            alt="User Profile">
                                                        <i class="edit-icon fas fa-pencil-alt"></i>
                                                    </div>

                                                    <div class="form">
                                                        <input type="file" class="upload-image" name="file"
                                                            style="display: none;">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-4 text-center remove-profile d-none">
                                        <a href="javascript:void(0)" title="remove picture"
                                            class="btn btn-danger btn-sm"> <i class="fa fa-remove"></i> </a>
                                    </div>
                                    {{-- <div class="col-md-12 col-border-top mt-4 text-center">

                                    </div> --}}

                                </div>

                            </div>

                        </div>
                    </section>


                </div>

            </form>

        </div>
    </div>


    {{-- clone --}}


    <div class="row clone d-none okay">
        <div class="col-md-2">
            <div class="form-group">
                <label for="relationship"></label>
                <select name="contact_person_relationship[]" style="width: 100%"
                    class="form-control contact_person_relationship form-control-sm">
                    <option value="">Select</option>
                    <option value="FATHER">FATHER</option>
                    <option value="MOTHER">MOTHER</option>
                    <option value="GUARDIAN">GUARDIAN</option>
                    <option value="WIFE">WIFE</option>
                    <option value="HUSBAND">HUSBAND</option>
                    <option value="FRIEND">FRIEND</option>

                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="Name"></label>
                <input name="contact_person_name[]" class="form-control contact_person_name form-control-sm" />
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="Occupation"></label>
                <input name="contact_person_occupation[]" class="form-control occupation form-control-sm" />
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="Phone"></label>
                <input type="number" name="contact_person_phone[]"
                    class="form-control contact_person_phone form-control-sm" />
            </div>
        </div>
        <div class="col-md-2" style="display: flex; align-items: center;">
            <a href="javascript:void(0)" class="bg-danger remove" style="font-size: 12px !important"><i
                    style="color:#ffff; padding-right: 0.6rem;
                        padding-left: 0.6rem;
                        padding-top: 0.4rem;
                        padding-bottom: 0.4rem;"
                    class="fa fa-remove"></i></a>
        </div>
    </div>


    {{-- end clone --}}



@section('scripts')
    <script>
        function checkAdmNo() {
            let admissionNumber = $('#admission_number').val();
            $.ajax({
                url: '{{ route('students.admission.duplicate.check') }}',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                method: 'POST',
                data: {
                    admission_number: admissionNumber
                },
                success: function(response) {
                    $('#loader').empty();
                    $('#loader').removeClass('badge-success')
                    $('#loader').removeClass('badge-danger')
                    $('#admission_number').attr('data-success', 0)
                    if (response.exists) {
                        $('#loader').html(
                            '<i class="fas fa-times text-white"></i> Admission Number Already Exists');
                        $('#loader').addClass('badge-danger')
                        $('#admission_number').attr('data-success', 0)
                    } else {
                        $('#loader').html('<i class="fas fa-check text-white"></i>');
                        $('#loader').addClass('badge-success')
                        $('#admission_number').attr('data-success', 1)
                    }
                },
                error: function(error) {
                    console.error(error);
                    $('#loader').empty();
                    $('#loader').removeClass('badge-success')
                    $('#loader').removeClass('badge-danger')
                    $('#admission_number').attr('data-success', 0)

                }
            });


        }

        $(document).ready(function() {

            checkAdmissionNumValidity();

            function checkAdmissionNumValidity() {

                $('#admission_number').on('input', function() {

                    let admissionNumber = $(this).val();
                    $('#loader').empty();

                    if (admissionNumber.trim() === '') {
                        return;
                    }
                    $('#loader').html('<i class="fas fa-spinner fa-spin"></i>');

                    $.ajax({
                        url: '{{ route('students.admission.duplicate.check') }}',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]')
                                .attr('content'));
                        },
                        method: 'POST',
                        data: {
                            admission_number: admissionNumber
                        },
                        success: function(response) {
                            $('#loader').empty();
                            $('#loader').removeClass('badge-success')
                            $('#loader').removeClass('badge-danger')
                            $('#admission_number').attr('data-success', 0)
                            if (response.exists) {
                                $('#loader').html(
                                    '<i class="fas fa-times text-white"></i> Admission Number Already Exists'
                                    );
                                $('#loader').addClass('badge-danger')
                                $('#admission_number').attr('data-success', 0)
                            } else {
                                $('#loader').html('<i class="fas fa-check text-white"></i>');
                                $('#loader').addClass('badge-success')
                                $('#admission_number').attr('data-success', 1)
                            }
                        },
                        error: function(error) {
                            console.error(error);
                            $('#loader').empty();
                            $('#loader').removeClass('badge-success')
                            $('#loader').removeClass('badge-danger')
                            $('#admission_number').attr('data-success', 0)

                        }
                    });
                });


            } 

            $('.edit-icon').click(function() {
                let input_file = $(this).parent().parent().find('.upload-image');

                input_file.change(function() {
                    const file = this.files[0];

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Update the image source with the selected file's data
                            $('#profile-image').attr('src', e.target.result);
                        };
                        reader.readAsDataURL(file);

                        $('.remove-profile').removeClass('d-none');
                    }
                });
                input_file.click();
            });

            // Add an event listener for deleting the image
            $('.remove-profile').click(function() {
                // Replace the image source with the default image source
                $('#profile-image').attr('src', '{{ asset('assets/img/icon_avatar.jpeg') }}');
                $(this).addClass('d-none')
            });




            $('.parent-container').on('click', '.add_button_img', function() {

                let clone = $('.clone').clone().removeClass('clone').removeClass('d-none').addClass(
                    'contact_people_row');
                $('.contact_people').append(clone);

                clone.find('select[name="contact_person_relationship[]"]').select2();


                clone.find('.remove').click(function() {
                    $(this).parent().parent().remove();
                })

            });

            $('.parent-container').on('click', '.remove', function() {
                $(this).parent().parent().remove();
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

        })




        let wizard;

        wizard = $('#wizard2').steps({
            headerTag: 'h3',
            bodyTag: 'section',
            autoFocus: true,
            titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
            labels: {
                finish: "Submit"
            },
            startIndex: getStoredStepIndex(),
            onInit: function(event, currentIndex) {
                // Populate form fields with stored data
                populateFormFields();
            },

            onStepChanging: function(event, currentIndex, newIndex) {
                let isNextButton = currentIndex < newIndex;


                if (isNextButton) {

                    saveDataForStep(currentIndex);
                    checkAdmNo();

                    // Step 1 form validation
                    if (currentIndex === 0) {
                        let admission_number_parsley = $('#admission_number').parsley();
                        let admission_number = $('#admission_number');
                        let fname = $('#firstname').parsley();
                        let lname = $('#lastname').parsley();
                        let middlename = $('#middlename').val();
                        let email = $('#std_email').val();
                        let nationality = $('#nationality').parsley();
                        let std_address = $('#std_address').val();
                        let tribe = $('#tribe').val();
                        let gender = $('#std_gender').val();
                        let clubs = $('#clubs').val();
                        let religion_sect = $('#religion_sect').val();
                        let house = $('#house').val();
                        let phone = $('#std_phone').val();
                        let dob = $('#dob').parsley();
                        let admitted_year = $('#admitted_year').parsley();
                        let religion = $('#religion').parsley();
                        let admission_type = $('#admission_type').parsley();
                        let isDisabled = $('#customCheck2').val();

                        //    console.log('success',admission_number.attr('data-success'))

                        if (fname.isValid() && nationality.isValid() && parseInt(admission_number.attr(
                                'data-success')) && admission_number_parsley.isValid() && lname.isValid() && dob
                            .isValid() && admitted_year.isValid() && religion.isValid() && admission_type
                            .isValid()) {

                            updateStoredStepIndex(newIndex);
                            populateFormFields()
                            return true;

                        } else {
                            fname.validate();
                            lname.validate();
                            dob.validate();
                            admitted_year.validate();
                            religion.validate();
                            nationality.validate();
                            admission_type.validate();
                            admission_number_parsley.validate();

                        }
                    }

                    // Step 2 form validation
                    if (currentIndex === 1) {

                        updateStoredStepIndex(newIndex);
                        populateFormFields()
                        return true;


                    }

                    if (currentIndex === 2) {

                        let class_id = $('#class_select').parsley();
                        let stream_id = $('#stream_select').parsley();

                        if (class_id.isValid() && stream_id.isValid()) {

                            updateStoredStepIndex(newIndex);
                            populateFormFields()
                            return true;

                        } else {

                            class_id.validate()
                            stream_id.validate()

                        }


                    }

                    if (currentIndex === 3) {

                        updateStoredStepIndex(newIndex);
                        populateFormFields()

                        return true;

                    }

                    // Always allow step back to the previous step even if the current step is not valid.
                } else {

                    updateStoredStepIndex(newIndex)
                    populateFormFields()
                    console.log('Previous button clicked');
                    return true;

                }

            },

            onFinished: function(event, currentIndex) {

                let url = '{{ route('students.all.steps.store') }}'
                let formdata = new FormData($('#form-submit')[0]);
                spark()
                $.ajax({
                    url: url,
                    processData: false,
                    contentType: false,
                    method: 'POST',
                    data: formdata,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },

                    success: function(res) {
                        unspark()
                        toast(res.msg, res.title);
                        if (res.state == 'done') {
                            // clear all the form field
                            $('#form-submit')[0].reset();
                            localStorage.removeItem('formDataStep1');
                            localStorage.removeItem('formDataStep2');
                            localStorage.removeItem('formDataStep0');
                            localStorage.removeItem('currentStep');

                        }
                    },
                    error: function(res) {
                        unspark()
                        console.log(res)
                    }
                })
            },
        });



        function saveDataForStep(stepIndex) {

            let formData;

            if (stepIndex == 0) {
                formData = {
                    firstname: $('#firstname').val(),
                    lastname: $('#lastname').val(),
                    middlename: $('#middlename').val(),
                    dob: $('#dob').val(),
                    admitted_year: $('#admitted_year').val(),
                    religion: $('#religion').val(),
                    nationality: $('#nationality').val(),
                    admission_no: $('#admission_number').val(),
                    gender: $('#gender').val(),
                    clubs: $('#clubs').val(),
                    house: $('#house').val(),
                    phone: $('#std_phone').val(),
                    email: $('#std_email').val(),
                    std_address: $('#std_address').val(),
                    admission_type: $('#admission_type').val(),
                    tribe: $('#tribe').val(),
                    religion_sect: $('#religion_sect').val(),
                    isDisabled: $('#customCheck2').val()

                };



            }

            if (stepIndex == 1) {

                formData = [];

                // Loop through each row
                $('.contact_people_row').each(function() {
                    // Extract data for each row
                    let relationship = $(this).find('select[name="contact_person_relationship[]"]').val();
                    let name = $(this).find('input[name="contact_person_name[]"]').val();
                    let occupation = $(this).find('input[name="contact_person_occupation[]"]').val();
                    let phone = $(this).find('input[name="contact_person_phone[]"]').val();

                    // Create an object for the row and push it to formData array
                    formData.push({
                        relationship: relationship,
                        name: name,
                        occupation: occupation,
                        phone: phone
                    });

                });


            }

            if (stepIndex == 2) {

                formData = {

                    class_id: $('#class_select').val(),
                    stream_id: $('#stream_select').val()

                }

            }

            let storageKey = 'formDataStep' + stepIndex;
            localStorage.setItem(storageKey, JSON.stringify(formData));
        }

        // Populate form fields with stored data for the current step
        function populateFormFields() {
            let currentIndex = getStoredStepIndex();
            let storageKey = 'formDataStep' + currentIndex;
            let storedData = localStorage.getItem(storageKey);

            if (storedData) {

                let formData = JSON.parse(storedData);


                if (currentIndex == 0) {

                    $('#firstname').val(formData.firstname);
                    $('#lastname').val(formData.lastname);
                    $('#dob').val(formData.dob);
                    $('#admission_number').val(formData.admission_no);
                    $('#religion').val(formData.religion).trigger('change');
                    $('#admitted_year').val(formData.admitted_year);

                    $('#nationality').val(formData.nationality);
                    $('#gender').val(formData.gender);
                    $('#clubs').val(formData.clubs).trigger('change');
                    $('#house').val(formData.house).trigger('change');
                    $('#admission_type').val(formData.admission_type).trigger('change');

                    $('#std_email').val(formData.email);
                    $('#std_phone').val(formData.phone);
                    $('#middlename').val(formData.middlename);
                    $('#religion_sect').val(formData.religion_sect).trigger('change');
                    $('#std_address').val(formData.std_address);
                    $('#tribe').val(formData.tribe);
                    $('#customCheck2').val(formData.isDisabled);

                }

                if (currentIndex == 1) {
                    $html = '';

                    formData.forEach((element, idx) => {
                        if (idx == 0) {

                            $btn = `<div class="col-md-2" style="display: flex; align-items: center; margin-top: 0.5em;">
                                    <a class="add_button_img" href="javascript:void(0)"> <i class="fa fa-plus-circle"></i> <span>Add</span></a>
                                </div>`;

                        } else {
                            $btn = `
                        <div class="col-md-2" style="display: flex; align-items: center;">
                    <a href="javascript:void(0)" class="bg-danger remove" style="font-size: 12px !important"><i style="color:#ffff; padding-right: 0.6rem;
                        padding-left: 0.6rem;
                        padding-top: 0.4rem;
                        padding-bottom: 0.4rem;" class="fa fa-remove"></i></a>
                    </div>`;
                        }

                        $html += ` <div class="row contact_people_row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="relationship">Relationship:</label>
                                        <input type="hidden" name="student_id" id="cnt_person_std_id">
                                        <select name="relationship[]" style="width: 100%"  class="form-control select2s relationship considerable form-control-sm">
                                            <option value="">Select</option>
                                            <option value="FATHER" ${element.relationship === 'FATHER' ? 'selected' : ''}>FATHER</option>
                                            <option value="MOTHER" ${element.relationship === 'MOTHER' ? 'selected' : ''}>MOTHER</option>
                                            <option value="GUARDIAN" ${element.relationship === 'GUARDIAN' ? 'selected' : ''}>GUARDIAN</option>
                                            <option value="WIFE" ${element.relationship === 'WIFE' ? 'selected' : ''}>WIFE</option>
                                            <option value="HUSBAND" ${element.relationship === 'HUSBAND' ? 'selected' : ''}>HUSBAND</option>
                                            <option value="FRIEND" ${element.relationship === 'FRIEND' ? 'selected' : ''}>FRIEND</option>
                                        </select>

                                     </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Name">Name:</label>
                                        <input name="name[]" value='${element.name}' class="form-control name form-control-sm" />
                                     </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="Occupation">Occupation:</label>
                                        <input name="occupation[]" value='${element.occupation}' class="form-control occupation form-control-sm" />
                                     </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Phone">Phone:</label>
                                        <input type="number" name="phone[]" value='${element.phone}' class="form-control phone form-control-sm"/>
                                     </div>
                                </div>

                                ${$btn}

                            </div> `;

                    });

                    $('.contact_people').html($html);

                }

                if (currentIndex == 2) {
                    console.log('yes its step 2')
                    $('#class_select').val(formData.class_id).trigger('change')
                    $('#stream_select').val(formData.stream_id).trigger('change')
                }



            }
        }

        // Get the stored step index or use 0 if not stored
        function getStoredStepIndex() {
            let storedStep = localStorage.getItem('currentStep');
            console.log('storedStep', storedStep)
            return storedStep ? parseInt(storedStep) : 0;
        }

        // Update stored step index
        function updateStoredStepIndex(stepIndex) {
            localStorage.setItem('currentStep', stepIndex);
        }

        // Get the current step index using currentIndex property
        function getCurrentStepIndex() {
            return wizard ? wizard.currentIndex : 0;
        }





        $('#religion').change(function() {

            let religion_id = $(this).val();

            spark()
            $.ajax(

                {
                    url: '{{ route('religion.sect.fetch') }}',
                    method: "POST",
                    data: {
                        religion_id: religion_id
                    },

                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    success: function(res) {
                        $('#rel_sect').css({
                            'display': 'block'
                        });
                        $('#religion_sect').html(res)
                        unspark();
                    },
                    error: function(res) {
                        console.log(res)
                        unspark()
                    }

                })

        })

        $('input[type="radio"]').change(function(event) {
            var selectedValue = $(this).val();
            if (selectedValue === 'single') {
                window.location.href = '{{ route('students.registration.single') }}';
            } else if (selectedValue === 'multiple') {

                window.location.href = '{{ route('students.registration.multiple') }}';
            }
        });
    </script>
@endsection
@endsection
