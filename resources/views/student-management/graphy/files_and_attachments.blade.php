@extends('layout.index')
@section('top-bread')
@include('student-management.profile_breadcrumb')
@endsection
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

</style>



@include('student-management.graphy.profile_nav')

<div class="sparkline9-list shadow-reset">
    <div class="sparkline9-hd">
        <div class="main-sparkline9-hd">
            <h1>Student Profile11</h1>
            <div class="sparkline9-outline-icon">
                <span class="sparkline9-collapse-link"><i class="fa fa-chevron-up"></i></span>
                <span><i class="fa fa-wrench"></i></span>
                <span class="sparkline9-collapse-close"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <div class="sparkline9-graph">
        <div class="basic-login-form-ad">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="user-profile-wrap shadow-reset">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="user-profile-img">
                                            <input type="hidden" id="student_uuid" name="student_uuid" value="{{ $student->student_uuid }}">
                                            @if ($imageUrl)
                                            <div style="display: flex; align-items:center; margin-left: 2.5rem;">
                                            <div class="image-container" style=" min-width: 20rem; max-width: 20rem;">
                                                <div>
                                                    <img src="{{ $imageUrl }}" alt="profile pic">
                                                    <i  class="fas fa-pencil-alt edit-icon"></i>
                                                </div>


                                                <form enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="file" class="upload-image" name="file" style="display: none;">
                                                </form>

                                            </div>
                                            </div>

                                            @else

                                            <div style="display: flex; align-items:center; margin-left: 2.5rem;">
                                                <div class="image-container" style=" min-width: 20rem; max-width: 20rem;">
                                                    <div>
                                                        <img style="object-fit: cover;" name="profile_image" id="profile-image" src="{{ asset('assets/img/icon_avatar.jpeg') }}" alt="User Profile">
                                                        <i class="edit-icon fas fa-pencil-alt"></i>
                                                    </div>

                                                    <form enctype="multipart/form-data" class="form">
                                                        @csrf
                                                        <input type="file" class="upload-image" name="file" style="display: none;">
                                                    </form>

                                                </div>
                                            </div>


                                            @endif



                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="user-profile-content">
                                            <h2>{{ $student->firstname.' '.$student->middlename.' '.$student->lastname   }}</h2>
                                            <table>
                                                <tr>
                                                    <td> <p class="profile-founder"> Class </p>  </td>
                                                    <td style="padding-left: 1rem;"> <p class="profile-founder">   <strong> {{ $student->class_name .' '. $student->stream_name }} </strong>      </p> </td>
                                                </tr>

                                                <tr>
                                                    <td>  <p class="profile-founder"> Age </p> </td>
                                                    <td style="padding-left: 1rem;"> <p class="profile-founder"> <strong> {{ $age }}  years </strong> </p> </td>
                                                </tr>

                                            </table>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="user-profile-content">

                                    <table>
                                        <tr>
                                            <td> <p class="profile-founder"> Religion: </p>  </td>
                                            <td style="padding-left: 1rem;"> <p class="profile-founder">   <strong> {{ $religion ? $religion : 'N/A'  }} </strong>      </p> </td>
                                            <td> <strong> {{  $religion_sect ? '-'.$religion_sect : ''  }} </strong>  </td>
                                        </tr>

                                        <tr>
                                            <td>  <p class="profile-founder"> Club/s: </p> </td>

                                            <td style="padding-left: 1rem;"> <p class="profile-founder"> <strong>
                                                @if (count($clubs))
                                                @foreach ($clubs as $club )
                                                <a style="padding: 4px 5px; border-radius: 5px;  color: darkblue; border: 1px dotted rgba(0, 0, 139, 0.596);"> {{ $club->name }} </a> &nbsp;
                                                @endforeach
                                                @else
                                                <span>N/A</span>

                                                @endif
                                                </strong> </p> </td>

                                        </tr>

                                    </table>


                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="analytics-sparkle-line user-profile-sparkline">
                                    <div class="analytics-content">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>


        </div>



        {{-- STEP 02 --}}


        <div class="user-prfile-activity-area mg-b-40 mg-t-30">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="user-profile-about shadow-reset">
                            <h2 style="color:#49668a"> <i style="color: #77bd5d" class="fa-solid fa-file-zipper"></i> &nbsp; Assigned Subjects  </h2>
                        </div>








                    </div>


                    <div class="col-lg-3">
                        <div class="user-profile-about shadow-reset">
                            <h2 style="color:#49668a"> <i style="color: #77bd5d" class="fa-solid fa-file-zipper"></i> &nbsp; Files and Attachments  </h2>
                        </div>

                    </div>

                    <div class="col-lg-6">
                        <div class="post-user-profile-awrap shadow-reset">
                            <div class="user-profile-post">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="user-profile-img-post">
                                            <a href="#"><img src="img/notification/3.jpg" alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="user-profile-post-name">
                                            <h2><a href="#"> <i style="color:#77bd5d" class="fa-solid fa-square-poll-vertical"></i> Results</a></h2>


                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <div class="admin-comment-month user-profile-post-action">
                                            <button class="comment-setting" data-toggle="collapse" data-target="#adminpro-demo1">...</button>

                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="user-profile-comment-list">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="user-profile-comment-img">
                                            <a href="#"><img src="img/notification/2.jpg" alt="">
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <div class="row profile-comment-mg">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="user-profile-comment-img">
                                            <a href="#"><img src="img/notification/6.jpg" alt="">
                                            </a>
                                        </div>
                                    </div>

                                </div>
                                <div class="row profile-comment-mg">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="user-profile-comment-img">
                                            <a href="#"><img src="img/notification/3.jpg" alt="">
                                            </a>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>




                    <div class="col-lg-6 d-none">
                        <div class="user-profile-mutual-friends shadow-reset mg-t-30">
                            <h2 style="color: #49668a"> <i style="color: #77bd5d" class="fa-solid fa-water-ladder"></i>  &nbsp;Class Promotions</h2>
                            <div class="mutual-friend-list">
                                <a href="#"><img src="img/notification/1.jpg" alt="">
                                </a>
                                <a href="#"><img src="img/notification/2.jpg" alt="">
                                </a>
                                <a href="#"><img src="img/notification/3.jpg" alt="">
                                </a>
                                <a href="#"><img src="img/notification/4.jpg" alt="">
                                </a>
                                <a href="#"><img src="img/notification/5.jpg" alt="">
                                </a>
                                <a href="#"><img src="img/notification/6.jpg" alt="">
                                </a>
                                <a href="#"><img src="img/notification/1.jpg" alt="">
                                </a>
                                <a href="#"><img src="img/notification/2.jpg" alt="">
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>




@section('scripts')
<script>

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
