@extends('layout.index')
@section('body')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css ">
<style>

.image-container {
    /* position: relative; */
    width: 200px; /* Adjust the size as needed */
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
 .th-hover:hover{
    cursor: pointer;
}

.col-border{
    border-right: 1px solid #17a2b8;
    top: 0;
}

.col-border-top{
    border-top: 1px solid #17a2b8;
}

</style>

<div class="card">
    <div class="card-header">
        <h6>User Profile</h6>
    </div>
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

                .col-display{
                    flex-direction: column;
                }
                .th-color{
                   color: #8094ae;
                   padding-top: 2rem !important;
                   padding-bottom: 2rem !important;
                }
                .th-center{
                    padding-top: 2rem !important;
                   padding-bottom: 2rem !important;
                   color: #526484;
                }

                th-last:hover{

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
                    } */



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
                                    <th class="th-color th-hover">Full Name</th> <th class="th-center th-hover">  {{ strtoupper($user->firstname.' '.$user->middlename.' '.$user->lastname )   }}</th> <th class="th-center ripple th-hover"><i class="fa-solid fa-angle-right" style="color:#8094ae"></i></th>
                                </tr>
                                <tr>
                                    <th class="th-color">Email</th> <th class="th-center"> {{ $user->email }}  </th> <th class="th-center"> <i style="color: #17a2b8" class="fa fa-lock"></i> </th>

                                </tr>
                                <tr>
                                    <th class="th-color th-hover">Phone #</th> <th class="th-center th-hover"> {{ $user->phone  }} </th> <th class="th-center th-hover ripple"> <i class="fa-solid fa-angle-right" style="color:#8094ae"></i> </th>
                                </tr>
                                <tr>
                                    <th class="th-color th-hover">Date Of Birth</th> <th class="th-center th-hover"></th> <th class="th-center th-hover ripple"> <i class="fa-solid fa-angle-right" style="color:#8094ae"></i></th>
                                </tr>
                                <tr>
                                    <th class="th-color th-hover">Address</th> <th class="th-center th-hover"> {{  $user->address }}  </th> <th class="th-center th-hover th-last"> <i class="fa-solid fa-angle-right" style="color:#8094ae"></i></th>
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

            <div  class="row">

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
                            <label for="address"> Address <i style="color:#069613  " class="fa-solid fa-location-dot"></i> </label>
                            <textarea rows="3" name="address" id="address" class="form-control" placeholder="Address..."></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone # </label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                    </div>
            </div>


          </div>
          <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
             <button type="button" class="btn btn-info">Save changes</button>
          </div>
       </div>
    </div>
 </div>

{{-- END EDIT MODAL --}}





@section('scripts')
<script>

$('.th-hover').click(function(){


    $('#m_modal_6').modal('show');



})



$('.edit-icon').click(function() {
  let input_file =  $(this).parent().parent().find('.upload-image');
  input_file.click();
});

let student_uuid = $('#student_uuid').val();

console.log('student_uuid',student_uuid)


$('.upload-image').change(function() {

    var file = this.files[0];
    let img = $(this).parent().parent().find('img');

    let form = $(this).parent().parent().find('form');
    form_data = new FormData(form[0]);
    let url = '{{  route('students.profile.update',[':uuid']) }}';
    url = url.replace(':uuid',student_uuid);

    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {

            $.ajax({
                url: url,
                type: "POST",
                timeout: 250000,
                processData: false,
                contentType: false,
                cache: false,
                data:form_data,
                    beforeSend: function(xhr) {
                        loadSpinner();
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                success:function(res){

                    if(res.state == 'done'){
                        img.attr('src', e.target.result);
                    }

                    stopSpinner();
                },

                error:function(){

                    stopSpinner();
                }
            })

        };

        reader.readAsDataURL(file);
    }
});




</script>

@endsection



@endsection
