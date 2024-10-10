@extends('layout.index')


@section('body')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css ">
<style>
    .radios-flex{
        display: flex;
        justify-content: space-evenly
    }

    .loader {
    width: 1em;
    height: 1em;
    margin: auto;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    position: absolute;
}


.disabled-link {
    pointer-events: none; /* Disable mouse events */
    cursor: not-allowed; /* Change cursor to indicate it's disabled */

  }

  .nav-pills .active{
    color:white !important;
  }


table {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

table td, #customers th {

  border: 1px solid #ddd;
  padding: 8px;

}

table tr:nth-child(even){background-color: #f2f2f2;}

/* #customers tr:hover {background-color: #ddd;} */

table th {

  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color:#008080 ;
  color: white;

}


.wizard_btns{
margin-top: 0.3rem;
}
.nav-pills .nav-link.active{

background-color:#04476a !important;
}
.success_bg{
    background-color: #069613 !important;
    color: white;
}
.status_btn{
    border-radius: 0px !important;
    pointer-events: none;
}

.nav_top{

    margin-top:.2rem;
}
.btn-sbmt{
    background-color:#04476a !important;

}

.status{

    background-color: red;
    height: 20px;
    width: 3.8rem;
    height: 1.6rem;

}

.form_wizard{
    width: 80%;
    margin: 1rem auto;
}

.cnt_prsn_section{
    margin-top: 1.2rem;
    background-color:#f7f7f7;
    padding: 0.6rem;
    width: 100%;
    margin-left: 0;

}

.description {
border-spacing: 0px;
border-collapse: separate;
border-top: 1px solid #999;

}

.required:after {
    content:" *";
    color: red;
  }

.description tr td {
border-bottom: 1px solid #999;
padding: 8;
}
.description  th  {
padding: 8;
border-bottom: 1px solid #999;
}

.structure_html{
    display: flex;
    flex-direction: horizontal;
}

.checkboxes{
                height: 15px;
            }


             .batch{
                display: flex;
                direction: horizontal;
               justify-content: space-between;
                width: 100%;
            }
            .spaces{
                padding: 0px 12px;
            }
            .div_spaces{
                display: flex;
            }


            .delete-icon {
  position: relative;
  overflow: hidden;
}

.delete-icon i {
  transition: all 0.3s ease-in-out;
  transform: scale(1);
}

.preview-icon i {

    transition: all 0.3s ease-in-out;
  transform: scale(1);

}

.preview-icon:hover i {
  transform: scale(1.5);
}



.delete-icon:hover i {
  transform: scale(1.5);
}
/* label{
    color:#b6babb
} */
.form-control{
    border: .5px solid #aaaaaa;
}


/* for the image */


.image-container {
    /* position: relative; */
    width: 200px; /* Adjust the size as needed */
}

#edit-icon {
    position: absolute;
    /* top: 10px; */
    right: 10px;
    font-size: 24px;
    color: #007bff;
    cursor: pointer;
}


</style>


<div class="sparkline9-list shadow-reset">
    <div class="sparkline9-hd">
        <div class="main-sparkline9-hd">
            <h1>Students Registration</h1>
            <div class="sparkline9-outline-icon">
                <span class="sparkline9-collapse-link"><i class="fa fa-chevron-up"></i></span>
                <span><i class="fa fa-wrench"></i></span>
                <span class="sparkline9-collapse-close"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <div class="sparkline9-graph">
        <div class="basic-login-form-ad">

            <div class="form-group-inner">
                <div class="row">
                    <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
                        <div class="pull-left radios-flex">
                            <div class="row" style="margin-right: 0.5rem">
                                <div class="col-lg-12">
                                    <div class="i-checks pull-left">
                                        <label>
                                            <input type="radio" {{ $activeRadio == 'single' ? 'checked' : '' }} value="single" name="a"> <i></i> Single Registration </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="i-checks pull-left">
                                        <label>
                                                <input type="radio" value="multiple" {{ $activeRadio == 'multiple' ? 'checked' : '' }} name="a"> <i></i> Multiple Registration </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

<div class="row">
    <div class="form_wizard" style="width: 100%">
        <div style="margin-left: 0; margin-top: 3rem;">
<div class="row">
    <div class="col-md-12">
        <div class="card" style="width: 100%">
            <div class="card-header">

            </div>
            <div class="card-body">
            <div class="row">
                <div class="col-md-2" id="step-nav">
                    <h4>Steps</h4>
                    <ul class="nav nav-pills flex-column" style="display: flex; flex-direction: column;">
                    <li class="nav-item">
                            <a style="color: white" class="nav-link disabled-link active" href="#step-1" data-toggle="pill"> <i class="font_icon d-none fas fa-check"></i> Personal Details</a>
                        </li>
                        <li class="nav-item nav_top">
                            <a class="nav-link disabled-link" href="#step-2" data-toggle="pill"><i class="font_icon d-none fas fa-check"></i> Contact Person Details</a>
                        </li>
                        <li class="nav-item nav_top">
                            <a class="nav-link disabled-link" href="#step-3"  data-toggle="pill"> <i class="font_icon d-none fas fa-check"></i> Class Details</a>
                        </li>
                        <li class="nav-item nav_top">
                            <a class="nav-link disabled-link" href="#step-4" data-toggle="pill"> <i class="font_icon d-none fas fa-check"></i> Attachments</a>
                        </li>
                    </ul>
                </div>



        <div class="col-md-10">
        <div class="tab-content">
        <div id="step-1" class="tab-pane active">

            <!-- form 1 -->
            <form id="form-1" class="form-container">
                <div class="loader_gif d-none">
                    <img style="max-width:15%" src="{{ asset('assets/images/cupertino_loader.gif') }}" alt="">
                </div>


<div style="display: flex">

<div>

    <div class="row">
        <div class="col-md-3 fade in animated zoomInLeft">
            <div  class="form-group">
                <label for="">Admission Number</label>
                <input readonly type="text" value="" name="admission_number" class="form-control form-control-sm">
            </div>
        </div>
        <div class="col-md-3 fade in animated zoomInLeft">
            <div  class="form-group">
                <label for="">Admission Date</label>
                <input type="date" data-must="1" name="admitted_year" id="admitted_year" class="form-control form-control-sm">
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="First Name">First Name: <span class="required"></span></label>
                <input data-must="1" type="text" name="first_name" class="form-control form-control-sm" id="first_name">
                <input type="hidden" name="stdnt_id" id="stdnt_id" class="form-control form-control-sm">

             </div>
        </div>
        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="Middle Name">Middle Name: </label>
                <input type="text" name="middle_name" class="form-control form-control-sm" id="middle_name">
             </div>
        </div>

        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="Last Name">Last Name: <span class="required"></span></label>
                <input data-must="1" type="text" name="last_name" class="form-control form-control-sm" id="last_name">
             </div>
        </div>

        <div class="col-md-3 fade in animated zoomInLeft">
            <label>Nationality</label>
            <select name="nationality" id="nationality" class="select2_demo_3 form-control">
                <option>Select</option>
                @foreach ($nationalities as $nationality )
                <option value="{{ $nationality->name }}">{{ $nationality->name }}</option>
                @endforeach
            </select>
    </div>
    </div>

    <div class="row">
        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="Address">Tribe:</label>
                <input type="text" name="address" id="std_address" class="form-control form-control-sm" id="address">
                <input type="hidden" name="std_address_id" id="std_address_id">
             </div>
        </div>

        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="Address">Address:</label>
                <input type="text" name="address" id="std_address" class="form-control form-control-sm" id="address">
                <input type="hidden" name="std_address_id" id="std_address_id">
             </div>
        </div>
        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="Phone">Phone:</label>
                <input type="text" name="phone" id="std_phone" class="form-control form-control-sm" id="phone">
                <input type="hidden" name="std_contact_id" id="std_contact_id" class="form-control form-control-sm" id="phone">
             </div>
             <input type="hidden" name="account_id" id="account_id">
             <input type="hidden" name="std_phone_id" id="std_phone_id">
        </div>

        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="Email">Email: </label>
                <input type="email" name="email" id="std_email" class="form-control form-control-sm" id="email">
                <span class="text-danger" id="email_error"></span>
                <input type="hidden" name="std_email_id" id="std_email_id">

             </div>
        </div>

        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="Date of Birth">Date of Birth: <span class="required"></span></label>
                <input data-must="1" type="date" min="{{ date('y-m-d') }}" name="dob" id="std_dob" class="form-control form-control-sm" id="dob">
             </div>
        </div>

        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="Gender">Gender: <span class="required"></span></label>
                <select data-must="1" name="gender" id="std_gender" class="form-control form-control-sm" id="">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
             </div>
        </div>
        <div class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="">Admission Type <span class="required"></span></label>
                <select data-must="1" name="admission_type" id="admission_type" class="form-control select2_demo_3 form-control-sm class_select">
                    <option value=""></option>
                    <option value="started">Started</option>
                    <option value="transfered">Transfered</option>
                    <option value="continuing">Continuing</option>
                </select>
             </div>
        </div>

        <div class="col-md-3 fade in animated zoomInLeft">

            <div class="form-group">
                <label for="">Religion</label>
                <select name="religion" id="religion" class="select2_demo_3 form-control form-control-sm">
                    <option value=""></option>
                    @foreach ($religions as $region )
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

    </div>

    <div class="row">

        <div id="rel_sect" style="display: none" class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="">Religion Sect</label>
                <select style="width: 100%" name="religion_sect" id="religion_sect" class="form-control select2_demo_3 form-control-sm">
                    <option value=""></option>
                </select>
            </div>

        </div>

        <div id="house" class="col-md-3 fade in animated zoomInLeft">
            <div class="form-group">
                <label for="">House</label>
                <select style="width: 100%" name="house" id="religion_sect" class="form-control select2_demo_3 form-control-sm">
                    <option value=""></option>
                    @foreach ($houses as $house )
                    <option value="{{ $house->id }}">{{ $house->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3 fade in animated zoomInLeft">

            <div class="form-group">
                <label for="">Clubsss</label>
                <select name="club[]" id="club" multiple class="select2_demo_3 form-control form-control-sm">
                    <option value=""></option>
                    @foreach ($clubs as $club )
                    <option value="{{ $club->id }}">{{ $club->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <div class="col-md-3 fade in animated zoomInLeft" style="margin-top: 3rem">
            <div class="pull-left" style="display: flex; justify-content: center; align-items: center;">
                <span> <input value="1" type="checkbox" style="height: 2.2rem; width: 3rem;" name="is_disabled"> </span>
                <label style="margin-left: 0.6rem;"> is Disabled </label>
            </div>
    </div>

    </div>


</div>


<div style="display: flex; align-items:center; justify-content:center; margin-left: 2.5rem;">
    <div class="image-container" >
        <h4 class="text-center"> Profile Pic </h4>
        <div style="border: 1px solid #b6babb;">
            {{-- min-height: 25rem; --}}
            <img style="object-fit: cover;" name="profile_image"  id="profile-image" src="{{ asset('assets/img/icon_avatar.jpeg') }}" alt="User Profile">
            <i id="edit-icon" class="fas fa-pencil-alt"></i>
        </div>

        <input type="file" id="upload-image" name="file" style="display: none;">
    </div>
</div>


</div>

{{--


     onStepChanging: function (event, currentIndex, newIndex) {
               if(currentIndex < newIndex) {
                 // Step 1 form validation
                 if(currentIndex === 0) {
                   let fname = $('#firstname').parsley();
                   let lname = $('#lastname').parsley();
                   let dob = $('#dob').parsley();
                   let admitted_year = $('#admitted_year').parsley();
                   let religion = $('#religion').parsley();


                   if(fname.isValid() && lname.isValid() && dob.isValid() && admitted_year.isValid() && religion.isValid() ) {
                     return true;
                   } else {
                     fname.validate();
                     lname.validate();
                     dob.validate();
                     admitted_year.validate();
                     religion.validate();
                   }
                 }

                 // Step 2 form validation
                 if(currentIndex === 1) {
                   var email = $('#email').parsley();
                   if(email.isValid()) {
                     return true;
                   } else { email.validate(); }
                 }
               // Always allow step back to the previous step even if the current step is not valid.
               } else { return true; }
             },

             onfinished: function(event, currentIndex){



             }


    --}}



{{--









$('#wizard2').steps({
             headerTag: 'h3',
             bodyTag: 'section',
             autoFocus: true,
             titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
             onStepChanging: function (event, currentIndex, newIndex) {
               if(currentIndex < newIndex) {
                 // Step 1 form validation
                 if(currentIndex === 0) {
                   let fname = $('#firstname').parsley();
                   let lname = $('#lastname').parsley();
                   let dob = $('#dob').parsley();
                   let admitted_year = $('#admitted_year').parsley();
                   let religion = $('#religion').parsley();


                   if(fname.isValid() && lname.isValid() && dob.isValid() && admitted_year.isValid() && religion.isValid() ) {
                     return true;
                   } else {
                     fname.validate();
                     lname.validate();
                     dob.validate();
                     admitted_year.validate();
                     religion.validate();
                   }
                 }

                 // Step 2 form validation
                 if(currentIndex === 1) {
                   var email = $('#email').parsley();
                   if(email.isValid()) {
                     return true;
                   } else { email.validate(); }
                 }
               // Always allow step back to the previous step even if the current step is not valid.
               } else { return true; }
             },

             onfinished: function(event, currentIndex){



             }

           });








    --}}

{{--

    $(document).ready(function () {
    // Retrieve form data and current step index from local storage on page load
    let storedData = localStorage.getItem('formData');
    let storedStep = localStorage.getItem('currentStep');

    try {
        storedData = JSON.parse(storedData) || {};
        storedStep = parseInt(storedStep) || 0;
    } catch (error) {
        storedData = {};
        storedStep = 0;
    }

    // Initialize wizard
    let wizard = $('#wizard2').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        autoFocus: true,
        titleTemplate: '<span class="number">#index#</span> <span class="title">#title#</span>',
        startIndex: storedStep,
        onInit: function (event, currentIndex) {

            console.log(storedData);

        }
    });

    // Save form data and current step index to local storage on step change
    wizard.on('stepChanging', function (event, currentIndex, newIndex) {
        // Add additional form fields to formData object if needed
        let formData = {
            firstname: $('#firstname').val(),
            // Add other fields as needed
        };

        localStorage.setItem('formData', JSON.stringify(formData));
        localStorage.setItem('currentStep', newIndex);

        // Add additional form validation as needed

        // Return true to allow step change, or return false to prevent it
        return true;
    });

    // Clear local storage on wizard completion
    wizard.on('finished', function (event, currentIndex) {
        localStorage.removeItem('formData');
        localStorage.removeItem('currentStep');
    });

});

    --}}


        </form>

        </div>


        <!-- STEP TWO -->
        <div id="step-2" class="tab-pane">

        <form id="form-2" class="form-container">
            <div class="loader_gif d-none">
                <img style="max-width:15%" src="{{ asset('assets/images/cupertino_loader.gif') }}" alt="">
            </div>

        <div id="contact_person_details_div">
            <div class="contact_people">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="relationship">Relationship:</label>
                        <input type="hidden" name="student_id" id="cnt_person_std_id">
                        <select name="relationship[]" style="width: 100%"  class="form-control select2_demo_3 relationship form-control-sm">
                            <option value="">Select</option>
                            <option value="FATHER">FATHER</option>
                            <option value="MOTHER">MOTHER</option>
                            <option value="GUARDIAN">GUARDIAN</option>
                            <option value="WIFE">WIFE</option>
                            <option value="HUSBAND">HUSBAND</option>
                            <option value="FRIEND">FRIEND</option>
                                {{-- @foreach ($relationships as $relationship )
                                <option value="{{ $relationship->id  }}">{{$relationship->name}}</option>
                                @endforeach --}}
                        </select>

                     </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="Name">Name:</label>
                        <input name="name[]" class="form-control name form-control-sm" />
                     </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="Occupation">Occupation:</label>
                        <input name="occupation[]" class="form-control occupation form-control-sm" />
                     </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="Phone">Phone:</label>
                        <input type="number" name="phone[]" class="form-control phone form-control-sm"/>
                     </div>
                </div>
                <div class="col-md-2" style="display: flex; align-items: center; margin-top: 3rem;">
                    <a class="add_button_img" href="javascript:void(0)"> <i class="fa fa-plus-circle"></i> <span>Add</span></a>
                </div>
            </div>
        </div>

            {{-- <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="MOther's Name">Mother's Name:</label>
                        <input type="text" name="mother_name" id="mother_name" class="form-control form-control-sm">
                        <input type="hidden" name="mother_contact_id" id="mother_contact_id">
                        <input type="hidden" name="mother_id" id="mother_id">
                     </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Occupation">Occupation:</label>
                        <input name="mother_occupation" id="mother_occupation" class="form-control form-control-sm" id=""/>
                     </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Phone">Phone:</label>
                        <input type="number" name="mother_phone" id="mother_phone" class="form-control form-control-sm"/>
                     </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Father's Name">Guardian's Name:</label>
                        <input type="text" name="guardian_name" id="guardian_name" class="form-control form-control-sm" id="fname">
                        <input type="hidden" name="guardian_contact_id" id="guardian_contact_id">
                        <input type="hidden" name="guardian_id" id="guardian_id">
                     </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Occupation">Occupation:</label>
                        <input name="guardian_occupation" id="guardian_occupation" class="form-control form-control-sm" id=""/>
                     </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Phone">Phone:</label>
                        <input type="number" name="guardian_phone" id="guardian_phone" class="form-control form-control-sm"/>
                     </div>
                </div>
            </div> --}}
             </div>

        </form>
                        {{-- <span style="float:right">
                        <!-- <button type="button" class=" wizard_btns btn btn-secondary btn-sm prev-step">Prev</button> -->
            <button type="button" style="background-color:#04476a !important;" class=" wizard_btns btn btn-primary btn-sm next-step">Next</button>
                    </span> --}}

        </div>



        {{-- NEW STEP 3 --}}

        <div id="step-3" class="tab-pane">
            <form id="form-3" action="" class="form-container" >
                <div class="loader_gif d-none">
                    <img style="max-width:15%" src="{{ asset('assets/images/cupertino_loader.gif') }}" alt="">
                </div>

            <div class="row" id="form-3_div">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Class">Class:</label>
                        <select data-must="1" name="students_class" id="class_select" style="width: 100%" class="class_select select2_demo_3 form-control form-control-sm" required>
                            <option value="">Select Class</option>
                            @foreach ($classes as $class )
                            <option value="{{$class->id}}">{{$class->name}}</option>
                            @endforeach
                        </select>
                     </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="streams">Stream:</label>
                        <select name="students_stream" id="stream_select" style="width: 100%" class="class_select form-control select2_demo_3 form-control-sm">
                            <option value="">Select Stream</option>
                            {{-- @foreach ($streams as $stream )
                            <option value="{{$stream->id}}">{{$stream->name}}</option>
                            @endforeach --}}
                        </select>
                        <input type="hidden" name="student_id"  class="student_id">
                     </div>
                </div>
        </div>
            </form>


        </div>

        <!-- STEP 4 -->

        <div id="step-4" class="tab-pane">
            <div class="loader_gif d-none">
                <img style="max-width:15%" src="{{ asset('assets/images/cupertino_loader.gif') }}" alt="">
            </div>
            <table id="customers" class="table">
                <thead class="thead-light">
                    <tr>
                        <th>Attachment Type  / Aina ya Kiambatanisho </th>
                        <th>Status</th>
                        <th style="width: 9%;"> </th>
                      </tr>
                </thead>

              <tr class="att_cover">
                <td class="attach_type">Passport</td>
                <td>
                <button id="btn-1" type="button" class="btn btn-sm btn-warning status_btn"> <i class="fa-sharp fa-solid fa-circle-xmark"></i> Not Attached    </button> <span id="td-1"></span>
                <input type="hidden" name="to_next" id="to_next">
                <input type="hidden" name="student_id" class="student_id">
            </td>
                <td> <button type="button" class="btn btn-sm btn-sbmt text-white attach"  data-title="PASSPORT" style="font-size: 0.77rem; height:25px; color:#ffff"> Attach </button>  </td>
              </tr>
              <tr class="att_cover">
                <td class="attach_type">Birth Certificate</td>
                <td>
                <button id="btn-2" type="button" class="btn btn-sm btn-warning status_btn"> <i class="fa-sharp fa-solid fa-circle-xmark"></i> Not Attached </button> <span id="td-2"> </span>
                </td>
                <td> <button type="button"  class="btn btn-sm btn-sbmt text-white attach" data-title="BIRTH CERTIFICATE" style="font-size: 0.77rem; height:25px; color:#ffff""> Attach </button> </td>
              </tr>
              </tr>
            </table>
            <span class="text-danger" id="attachment_error" style="float: left; margin-top:1rem">


            </span>
            {{-- <span style="float: right;">
            <!-- <button type="button" class=" wizard_btns btn btn-secondary btn-sm prev-step">Prev</button> -->
            <button type="button" style="background-color:#04476a !important;" class=" wizard_btns btn btn-primary btn-sm next-step">Next</button>

            </span> --}}

            </div>



        <!-- STEP FOUR -->

        <div id="step-5" class="tab-pane">

            <form id="form-5" action="" class="form-container" >
                <div class="loader_gif d-none">
                    <img style="max-width:15%" src="{{ asset('assets/images/cupertino_loader.gif') }}" alt="">
                </div>
            <div class="row">
        <div class="col-md-12">
        <div class="loader  text-center">
              <div class="spinner-border my-auto iphone-loader">
                 <span class="sr-only">Loading...</span>
              </div>
           </div>
        </div>
        </div>
            {{-- <div class="row" id="settings_part">
                <div class="col-md-12">

                    <table class="table">
                        <tr>
                            <td>Enable SMS Features</td>
                        <td> <input id="student_is_sms_checked" name="student[is_sms_enabled]" type="checkbox">  </td>
                        </tr>

                        <tr>
                            <td>Assign Transport</td>
                        <td> <input id="student_is_sms_checked" name="student[is_sms_enabled]" type="checkbox">  </td>
                        </tr>

                        <img align="absmiddle" alt="Loader" border="0" id="loader2" src="/images/loader.gif?60630d2a1025b2d1855181295e0dc963" style="display: none;">
                    </table>
                </div>
        <div class="col-md-4">
            <div class="form-group">
                <input type="hidden" name="client_id" class="client_id">
                <input type="hidden" name="class_id" id="class_id" class="class_id">
                <input type="hidden" name="student_id" id="student_id" class="student_id">
            </div>

        </div>

        </div> --}}
        <span style="text-align: center"> <p style="font-size: .8rem;
            color: darkslateblue;
            background: #e1e1e1;"> Assigned Fees Structure For Student  </p>  </span>

            <div class="structure_html">
                <div id="innerHTML"></div>

        </div>

            </form>
        </div>
        <!-- </form> -->
        </div>
        </div>
        </div>

        ​</div>

        <div class="card-footer">
            <span style="float: right;">
                <button data-prev-step = "0" id="prev-step" type="button" class="wizard_btns btn btn-secondary btn-sm prev-step d-none">Prev</button>
                <button type="button" id="finalize_btn" class=" wizard_btns btn btn-sm btn-success d-none"> <i class="fa-solid fa-clipboard-list"></i> complete</button>
                    <button type="button" id="the_next_btn" data-step="1" style="background-color:#04476a !important;" class=" wizard_btns btn btn-primary btn-sm next-step">  Next</button>
                </span>
        </div>

        </div>

    </div>
</div>


        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="attachments_modal" >
          <div class="modal-dialog"  role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close cancel" data-bs-dismiss="modal" aria-label="Close">
                  <span aria-hidden="1">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="multipart_form" enctype="multipart/form-data" class="form-content">
                    <div class="loader_gif d-none">
                        <img style="max-width:15%" src="{{ asset('assets/images/cupertino_loader.gif') }}" alt="">
                    </div>
                <div class="mb-12">
                    <span class="text-danger" id="file_attach_err"></span>
               <input class="form-control form-control-sm" style="height: 45px; font-size:10px;" name="attachment_file" id="file_attach" type="file">
               <input type="hidden" name="attachments" class="attachments">
               <input type="hidden" class="class_hidden" name="type" id="type">
               <input type="hidden" name="client_id" class="client_id">
                </div>
                </form>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm btn-sbmt" id="upload"> <i class="fa-solid fa-upload"></i>Upload</button>
                <button type="button" class="btn btn-secondary btn-sm cancel" data-bs-dismiss="modal">Cancel</button>
              </div>
            </div>
          </div>
        </div>




        <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">

                </div>

            </div>

        </div>
    </div>

</div>
</div>


<div class="row">
    {{-- <div class="col-md-4">
        <div class="form-group">
            <label for="Father's Name">Father's Name:</label>
            <input type="text" name="father_name" class="form-control form-control-sm" id="fname">
            <input type="hidden" name="father_contact_id" id="father_contact_id">
            <input type="hidden" name="father_id" id="father_id">
            <input type="hidden" name="student_id" id="student_id" class="student_id">

         </div>
    </div> --}}


    <div class="row clone d-none okay">
        <div class="col-md-2">
            <div class="form-group">
                <label for="relationship"></label>
                <select name="relationship[]" style="width: 100%" class="form-control relationship form-control-sm">
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
                <input name="name[]" id="name" class="form-control form-control-sm" />
             </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="Occupation"></label>
                <input name="occupation[]"  class="form-control occupation form-control-sm" />
             </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="Phone"></label>
                <input type="number" name="phone[]" class="form-control phone form-control-sm"/>
             </div>
        </div>
        <div class="col-md-2" style="display: flex; align-items: center; margin-top: 2.5rem;">
            <a href="javascript:void(0)" class="bg-danger remove" style="font-size: 12px !important"><i style="padding-bottom: 0.5rem; padding-right: 1rem; padding-left: 1rem; padding-top: 0.5rem;" class="fa fa-remove"></i></a>
        </div>
    </div>

</div>



@section('scripts')

<script>


$('#class_select').change(function(){
    let class_id = $(this).val()

    $.ajax(

{
    url:'{{ route('academic.class.streams.fetch') }}',
    method:"POST",
    data:{
        id:class_id
},

beforeSend: function(xhr) {
    showLoader();
    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},
success:function(res){
$('#stream_select').html(res)
hideLoader();
},
error:function(res){
    console.log(res)
}

})




})


$('.add_button_img').click(function(){

let clone  = $('.clone').clone().removeClass('clone').removeClass('d-none');
$('.contact_people').append(clone);

$(document).ready(function(){

$('select[name="relationship[]"]').addClass('select2_demo_3')

})


clone.find('.remove').click(function(){
    $(this).parent().parent().remove();
})

})




/* default date */
defaultTodaysDate('admitted_year');
setMaxTodayDate('std_dob');

$('input[type="radio"]').change(function(event) {
    alert('changed')
    var selectedValue = $(this).val();
    if (selectedValue === 'single') {
        window.location.href = '{{ route('students.registration.single')  }}';
    } else if (selectedValue === 'multiple') {

        window.location.href = '{{ route('students.registration.multiple')  }}';
    }
});


/* THE WIZARD MOVEMENTS */

let currentStep = 1;
let totalSteps = 4;


/* BACK TO STEP 01 */

document.addEventListener("DOMContentLoaded", function() {
    // Load stored data on page load
    const storedData = localStorage.getItem("formWizardData");
    if (storedData) {
        const { step, formData } = JSON.parse(storedData);

    }



$('.prev-step').click(function(){

    let currentStep = $(this).data('prev-step');
    nextCurrentStep = currentStep + 1;
    $('#step-' + nextCurrentStep).removeClass('active');
    $('#step-nav a[href="#step-' + currentStep + '"]').removeClass('success_bg').addClass('text-white').addClass('active');
    $('#step-nav a[href="#step-' + currentStep + '"]').find('.font_icon').removeClass('d-none').addClass('text-white');

    if ($(this).data('prev-step') == 1) {




    }

// const formData = new FormData(formWizard);
//     const currentStep = parseInt(formData.get("step"));
//     const dataToStore = {
//         step: currentStep,
//         formData: Array.from(formData.entries())
//     };
//     localStorage.setItem("formWizardData", JSON.stringify(dataToStore));





    // Move to the previous step
    // const prevStep = currentStep - 1;
    // const prevStepElement = document.querySelector(`[data-step="${prevStep}"]`);
    // if (prevStepElement) {
    //     prevStepElement.style.display = "block";
    //     // Hide the current step or adjust visibility as needed
    // }


})





})



$(document).ready(function(){

$('#the_next_btn').click(function(){

currentStep = $(this).attr('data-step');

// console.log(validateStep(currentStep))

if (validateStep(currentStep)) {

    $(this).addClass("disabled");

    $('#step-' + currentStep).find('.font_icon').removeClass('d-none');

    if(currentStep == 1){

        let form = $('#form-1')[0];
        let form_data = new FormData(form);
        let url = '{{ route('students.step.1.store')  }}';

        let parameters = {
            url:url,
            form_data : form_data,
            currentStep : currentStep
        }
        step1FormWizard(parameters)

    }


    if (currentStep == 2) {

        let form = $('#form-2')[0];
        let form_data = new FormData(form);
        let url =  '{{ route('students.step.2.store')  }}';

        let parameters = {
            url:url,
            form_data : form_data,
            currentStep : currentStep
        }

        stepTwoFormWizard(parameters)
    }


if (currentStep == 3) {

let form = $('#form-3')[0];
let form_data = new FormData(form);
let url =  '{{ route('students.step.3.store')  }}';

let parameters = {
    url:url,
    form_data : form_data,
    currentStep : currentStep
}

stepThreeFormWizard(parameters)

}








}



})


})




/* validation Function */

function validateStep(step) {
let isValid = true;

if(step == 4){

return isValid;

}else{

let elements = $("#form-" + step).find("input, select");
   elements.each(function() {
        if ( $(this).data('must') &&  !$(this).val()) {
            isValid = false;
            $(this).addClass('is-invalid');
        }
        else {
            $(this).removeClass('is-invalid');
        }
    });
    return isValid;
}


}




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


$('#religion').change(function(){

    let religion_id = $(this).val();

    $.ajax(

    {
        url:'{{ route('religion.sect.fetch') }}',
        method:"POST",
        data:{
        religion_id:religion_id
    },

    beforeSend: function(xhr) {
        showLoader();
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },
    success:function(res){
    $('#rel_sect').css({'display':'block'});
    $('#religion_sect').html(res)
    hideLoader();
    },
    error:function(res){
        console.log(res)
    }

    })




})








$('.prev-step').click(function() {
    if(currentStep > 1) {
        $('#step-' + currentStep).removeClass('active');
        currentStep--;
        $('#step-' + currentStep).addClass('active');
        $('.nav-link').removeClass('active');
        $('#step-nav a[href="#step-' + currentStep + '"]').addClass('active');
    }
});


$('#finalize_btn').click(function(){
    $(this).addClass("disabled");
    $('.loader_gif').removeClass("d-none");
    setTimeout(function(){
        $('.loader_gif').addClass("d-none");
        swal.fire({
        title: 'success',
        text: 'student admitted',
        type: 'warning',
        confirmButtonText: 'OK'
    }).then(function(result) {
        if (result.value) {
            swal.showLoading();
            setTimeout(function(){
                    $('#finalize_btn').removeClass("disabled");
                    swal.hideLoading();
                    window.location.href = "";
                }, 100);
        }


    });

    }, 800)
    $(this).addClass("disabled");

    });






$('.attach').each(function(){
    let btn = $(this);
    $('.loader').addClass("d-none");
    btn.click(function (params) {
        let title_text = btn.data('title');
        let modal_clone = $('#attachments_modal');
        modal_clone.find('.modal-title').text(title_text);
        modal_clone.find('.class_hidden').val(title_text);
        modal_clone.modal('show');
        $('#file_attach_err').text('');
        $('.attachments').val(title_text);
        $('#multipart_form')[0].reset();
    });

    });


    $('#upload').click(function(){
        let btn = $(this);
        btn.addClass("disabled");
        $('.loader_gif').removeClass("d-none");
        let form = $('#multipart_form')[0];
        let form_data = new FormData(form);
        let init_url = '';

        let url =  init_url.replace(':id',$('#student_id').val());
        if (form_data) {
        $.ajax({

            type:'POST',
            processData: false,
            contentType: false,
            data:form_data,
            url:url,
            success:function(response){
                console.log(response)

                if(response.state == 'Done'){

                $('.loader_gif').addClass("d-none");
                $('#upload').removeClass('disabled');

                toastr.success(response.msg, response.title);

                    $('.att_cover').each(function(){
                         let type = response.type;
                         let elem = '';
                          if($(this).children('.attach_type').text().toLowerCase() == type.toLowerCase()){

                            if(type.toLowerCase() == 'passport'){
                                elem = $('#td-1');

                            }else if(type.toLowerCase() == "birth certificate"){
                                elem = $('#td-2');
                            }
                            elem.html(response.btns);

                            let success_upload = `<i class="fa-sharp fa-solid fa-circle-check"></i> Attached`;
                            $(this).find('.status_btn').removeClass('btn-warning').addClass('btn-success').html(success_upload);

                         } 

                         $('#attachments_modal').modal('hide'); 
                       });


                }

                if(response.state == 'Fail'){

                    $('.loader_gif').addClass("d-none");
                    $('#upload').removeClass('disabled');

                    toastr.info(response.msg, response.title);

                }

                if(response.state == 'Error'){

                    $('.loader_gif').addClass("d-none");
                    $('#upload').removeClass('disabled');
                    toastr.danger(response.msg, response.title);

                }

            },
            error:function(response){
                console.log(response);
                $('.loader_gif').addClass("d-none");
                $('#upload').removeClass('disabled');
            }
        });


    }

    });



</script>

@endsection



@endsection
