@extends('layout.index')

@section('body')

<style>
    .radios-flex{
        display: flex;
        justify-content: space-evenly
    }

    .custom-container {
    border: 1px solid #e9eef5; /* Replace #000 with your desired border color */
    border-radius: 0.25rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding-bottom: 2rem;
    padding-top: 2rem;
}

th{

    position: -webkit-sticky;
    position: sticky;
    top: 0;
    background-color: #f0f0f0;

}



@keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .btn-icon .fa {
            transition: transform 0.3s ease-in-out;
            animation: bounce 1s infinite;
        }



</style>


<div class="card mt-4">
    <div class="card-header">
        MARKS ENTRACE
    </div>

    <div class="card-body">
      @include('results.nav')


      <div class="row mg-t-10">
        <div class="col-md-4">
           <div class="custom-control custom-radio">
              <input name="rdio" type="radio" {{ $activeRadio == 'template' ? 'checked' : '' }} value="template" name="a" class="custom-control-input" id="radio1">
              <label style="cursor: pointer"  class="custom-control-label" for="radio1">By Template</label>
           </div>
        </div>
        <!-- col-3 -->
        <div class="col-md-4 mg-t-20 mg-lg-t-0">

           <div class="custom-control custom-radio">
              <input name="rdio" type="radio" value="system" {{ $activeRadio == 'system' ? 'checked' : '' }} name="a" class="custom-control-input" id="radio2">
              <label style="cursor: pointer" class="custom-control-label" for="radio2">By System</label>
           </div>
        </div>
     </div>



    <div class="row hide-in clearfix" style="margin-top: 4rem">
        <div class="col-md-12">

            <div id="accordion">
                <div class="card mb-2">
                   <div class="card-header">
                      <a class="text-dark collapsed" data-toggle="collapse" href="#accordion1" aria-expanded="false" data-original-title="" title="" data-init="true">
                        <i class="fa-solid fa-file-excel" style="color: cadetblue"></i> Download Template
                      </a>
                   </div>
                   <div id="accordion1" class="collapse" data-parent="#accordion" style="">
                      <div class="card-body">
                        <input type="hidden" name="class" id="class" value="{{ $clasxs_id }}">
                        <input type="hidden" name="subjects" id="subjects" value="{{ $subject_id  }}" >

                        <table class="display compact responsive nowrap" style="width:100%">

                            <tr>
                                <td style="font-weight:bold"> Class</td>
                                <td style="font-weight:bold">Subject</td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>{{ $class_model->name }} - {{ $stream_model->name  }} </td>
                                <td>{{ $subject_model->name }}</td>
                                <td>  <span>
                                    <a href="javascript:void(0)" onclick="generateFile('excel')" type="button" style="background: #5cb85b" class="btn btn-sm btn-icon btn-success">
                                        <i class="fa-solid fa-cloud-arrow-down"></i> Template
                                    </a>
                                </span></td>
                            </tr>

                        </table>


                      </div>
                   </div>
                </div>

                <div class="card mb-2">
                   <div class="card-header">
                      <a class="text-dark collapsed" data-toggle="collapse" href="#accordion2" aria-expanded="false">
                        <i class="fa-solid fa-square-poll-vertical" style="color: #008080;"></i> Upload Results
                      </a>
                   </div>
                   <div id="accordion2" class="collapse" data-parent="#accordion" style="">
                      <div class="card-body">

                        <div class="col-md-12">
                            <table style="width:100%" class="table">
                            <tr>
                                <th style="background: #39ae53; color:white ">Academic Year</th>
                                <th style="background: #39ae53; color:white">Term</th>
                                <th style="background: #39ae53; color:white">Class</th>
                                <th style="background: #39ae53; color:white">Stream</th>
                                <th style="background: #39ae53; color:white">Exam Type</th>
                                <th style="background: #39ae53; color:white">Subject</th>
                            </tr>

                            <tr>
                                <td>{{ $acdmc_year->name }}</td>
                                <td>{{ $semester->name }}</td>
                                <td>{{ $class_model->name }}</td>
                                <td>{{ $stream_model->name }}</td>
                                <td>{{ $exam->name }}</td>
                                <td>{{ $subject_model->name }}</td>
                            </tr>

                            </table>


                        </div>

                        {{-- FORM --}}

                        <form id="fileUploadForm" method="POST" action="{{ route('results.sytem.excel.import') }}" enctype="multipart/form-data">
                            @csrf

                            <audio id="notification-audio"  src="{{ asset('assets/sounds/sound1.ogg') }}" autoplay="false" preload="auto"></audio>

                            <input type="hidden" name="marking_to" id="marking_to"  value="{{ $marking_to }}">
                            <input type="hidden" name="term" value="{{ $semester->id }}" id="term">
                            <input type="hidden" name="grading_profile" id="grading_profile" value="{{ $grade_group_id }}">
                            <input type="hidden" name="class_sbmt" id="class_sbmt" value="{{ $clasxs_id }}">
                            <input type="hidden" name="subjects_sbmt" id="subjects_sbmt" value="{{ $subject_id  }}" >
                            <input type="hidden" name="exams_sbmt" id="exams_sbmt" value="{{ $exam->id }}">
                            <input type="hidden" name="stream_id" id="stream" value="{{ $stream_id }}">
                            <input type="hidden" name="this_uuid" id="this_uuid" value="{{ $exam_schedule->uuid }}">
                            <input type="hidden" name="academic_year" id="year" value="{{ $exam_schedule->academic_year_id }}">
                            <input type="hidden" name="sp" id="sp" value="{{ $sp }}">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">File</label>
                                    <input type="file" name="file" id="inputFile" class="form-control form-control-sm" required>
                                   <span id="file_attach_err" class="text-danger">  </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="progress">
                                        <div style="background: #f9a11c!important" class="progress-bar progress-bar-striped  progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"> <span id="progress_count"  style="color: black; display:flex; position:absolute; left:50%; font-size: 1.5rem;">0%</span> </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" style="display: flex; justify-content:end">
                                <span>
                                    <button type="button" id="importBtn" disabled style="background: #008080; color:white" class="btn btn-custon-rounded-four btn-icon">
                                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload
                                    </button>
                                </span>
                            </div>


                        </form>

                        {{-- END FORM --}}

                        <div class="col-md-4 custom-container in animated zoomInDown d-none">
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom: 2rem">

                                    <div id="resultMessage" style="display: flex; justify-content:space-between">

                                        <div style="display: flex; justify-content:space-around">
                                            <div  style="font-size: 1.8rem; color:#00a65a"> Success: </div>
                                            <div style="font-size: 1.8rem;"> <span id="pass_div">0</span>  &nbsp; <a id="view" href="javascript:void(0)" style="background: #49668a" class="btn btn-success d-none  btn-sm"> View </i></a> </div>
                                        </div>

                                        <div style="display: flex; justify-content:space-around">
                                            <div style="font-size: 1.8rem; color:#f68749"> Fail: </div>
                                            <div style="font-size: 1.8rem;" id="fail_div">0</div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <a id="download_failed" style="width:100%; background:#f9f3e0" onclick="generateExcel('excel')" href="javascript:void(0)" class="btn btn-icon d-none btn-sm"> <i class="fa fa-download"></i> Download Failed Upload Excel </a>
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
</div>
</div>







{{-- Modal --}}


<div id="academic_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-color-modal bg-color-1">
                <h4 class="modal-title">Validation hapa </h4>
                <div class="modal-close-area modal-close-df">
                    <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                </div>
            </div>
            <div class="modal-body">
                <form action="#" id="cform">
                <div class="row">
                    <div class="col-md-12" id="preview_excel" style="overflow: scroll;">


                    </div>
            </form>
            </div>
            <div class="modal-footer">
                <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn btn-sm" href="#">Cancel</a>
                {{-- <a href="javascript:void(0)" id="sbmt-btn"  class="btn btn-primary btn-sm">Submit</a> --}}
                <button type="button" id="sbmt-btn" disabled style="background: #047878; color:white" class="btn btn-sm btn-custon-rounded-four btn-icon">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Submit
                </button>
            </div>
        </div>
    </div>
</div>



{{-- end --}}



@section('scripts')

<script>

function playSound() {

let audio = new Audio('{{ asset('assets/sounds/sound1.ogg') }}');
audio.play();

}



/* STORE */
let audioElement = document.getElementById('notification-audio');
audioElement.muted = true;

function generateExcel(file_type){
let url = '{{ route('results.sytem.excel.import.errors') }}';
url = url+"?file_type="+file_type;
window.open(url,'_blank');

}


$('input[type="radio"]').change(function(event) {

let uuid = $('#this_uuid').val();
let subject = $('#subjects_sbmt').val();
let class_shule = $('#class_sbmt').val();
let stream = $('#stream').val()
let sp = $('#sp').val()
let gg = $('#grading_profile').val()

var selectedValue = $(this).val();
if (selectedValue === 'template') {

        let init_url = '{{ route('results.template.export.index',[':uuid',':sp',':gg',':subject',':class',':stream'])  }}';
        let url = init_url.replace(':uuid',uuid).replace(':subject',subject).replace(':class',class_shule).replace(':stream',stream).replace(':sp',sp).replace(':gg',gg);
        window.location.href = url;

} else if (selectedValue === 'system') {

        let init_url = '{{ route('results.sytem.entry.index',[':uuid',':sp',':gg',':subject',':class',':stream'])  }}';
        let url = init_url.replace(':uuid',uuid).replace(':subject',subject).replace(':class',class_shule).replace(':stream',stream).replace(':sp',sp).replace(':gg',gg);
        window.location.href = url;
}
});


$('#class').change(function(e){


$.ajax({

    url:'{{ route('classes.streams.fetch')  }}',
    beforeSend: function(xhr) {
            showLoader();
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

    data:{

        id : $(this).val(),
        subject_id : $('#subjects').val()

    },
    type:'POST',

    success:function(res){
        hideLoader();

        $('#stream').html(res.streams)
        $('#subjects').html(res.subjects)
    },

    error: function(res){
        hideLoader();
        console.log(res)

    }

})


})


function generateFile($file_type){ 

let class_id = $('#class').val();

let stream_id = $('#stream').val();
let subject_id = $('#subjects').val();
let file_type = $file_type;

let url = '{{ route('results.template.export') }}';
url = url+"?file_type="+file_type+"&class_id="+class_id+"&subject_id="+subject_id+"&stream_id="+stream_id;
window.open(url,'_blank');

}



$('#year').change(function(){

let year_id = $(this).val()

$.ajax({

url: '{{ route('links.terms')  }}',
method:'POST',
data:{
id : year_id
},

beforeSend: function(xhr) {
        showLoader();
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },

success:function(res){
hideLoader();
$('#term').html(res).trigger('change');
},

error: function(){




}


})

})




$('#class_sbmt').change(function(e){
    spark()

$.ajax({

    url:'{{ route('classes.streams.fetch')  }}',
    beforeSend: function(xhr) {
            showLoader();
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

    data:{
        id : $(this).val(),
    },
    type:'POST',

    success:function(res){
        unspark();
        $('#stream_sbmt').html(res.streams)
        $('#subjects_sbmt').html(res.subjects)
    },

    error: function(res){
        unspark();

        console.log(res)

    }

})


})


/* NOW UPLOAD WITH PROGRESS BAR */

        $(function () {


                    $(document).ready(function () {

                         // Disable the submit button initially
                        // $('#sbmt-btn').prop('disabled', true);
                        // $('#sbmt-btn').attr('disabled',true);

                        $('#importBtn_today').click(function () {
                        spark();

                        let form_data = new FormData($('#fileUploadForm')[0]);

                        $.ajax({
                            processData: false,
                            contentType: false,
                            type: 'POST',
                            data: form_data,
                            url: '{{ route('results.sytem.excel.import.preview') }}',
                            beforeSend: function (xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                            },
                            success: function (res) {
                                unspark();

                                $('#preview_excel').html(res);
                                $('#academic_modal').modal('show');

                                // Loop through the data and validate each row
                                $('table tr').each(function () {
                                    let marksElement = $(this).find('.marks');
                                    // let validationElement = $(this).find('.validation');
                                    let validationElement = $(this).find('.validation-cell');

                                    let marks = marksElement.val();
                                    
                                    if (marks !== undefined && marks !== null && marks.trim() !== '' && (marks.match(/^[0-9]+$/) || marks === 'x' || marks === 's')) {
                                    
                                        validationElement.html('<i style="color:#069613" class="fa-solid fa-circle-check"></i>');
                                    } else {
                                        validationElement.html('<i style="color:#bc0a32" class="fa fa-times-circle"></i>'); // Display the red 'x' icon

                                    }

                                // Change the validation icon to tick when marks are filled
                                    marksElement.on('input', function () {
                                            countBlank();

                                        if ($(this).val().trim() !== '' && ($(this).val().match(/^[0-9]+$/) || $(this).val() === 'x' || $(this).val() === 's')) {
                                            validationElement.html('<i style="color:#069613" class="fa-solid fa-circle-check"></i>');
                                        } else {
                                            validationElement.html('<i style="color:#bc0a32" class="fa fa-times-circle"></i>'); // Display the red 'x' icon
                                            allValidated = false;
                                        }

                                        // Disable or enable the submit button based on validation
                                        // $('#sbmt-btn').prop('disabled', !allValidated);
                                        $('#sbmt-btn').attr('disabled', !allValidated);
                                    });
                                });


                                // Check if all rows are validated to enable the submit button
                                var allValidated = true;
                                $('table tr').each(function () {
                                    let validationElement = $(this).find('.validation-cell');
                                    if (validationElement.find('.fa-times-circle').length > 0) {
                                        // console.log(allValidated);
                                        allValidated = false;

                                        return false; // exit loop early
                                    }
                                });
                            // $('#sbmt-btn').prop('disabled', !allValidated);
                            $('#sbmt-btn').attr('disabled', !allValidated);
                             
                            },
                            error: function (res) {
                                unspark();
                                console.log(res);
                            }
                        });


                        
            });

            // now 

            $(document).ready(function () {
    // Disable the submit button initially
    // $('#sbmt-btn').attr('disabled', true);

    $('#importBtn').click(function () {
        spark();

        let form_data = new FormData($('#fileUploadForm')[0]);

        $.ajax({
            processData: false,
            contentType: false,
            type: 'POST',
            data: form_data,
            url: '{{ route('results.sytem.excel.import.preview') }}',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function (res) {
                unspark();

                $('#preview_excel').html(res);
                $('#academic_modal').modal('show');

                // Loop through the data and validate each row
                $('table tr').each(function () {
                    let marksElement = $(this).find('.marks');
                    let validationElement = $(this).find('.validation-cell');

                    marksElement.on('input', function () {
                        countBlank();

                        if ($(this).val().trim() !== '' && ($(this).val().match(/^[0-9]+$/) || $(this).val() === 'x' || $(this).val() === 's')) {
                            validationElement.html('<i style="color:#069613" class="fa-solid fa-circle-check"></i>');
                        } else {
                            validationElement.html('<i style="color:#bc0a32" class="fa fa-times-circle"></i>'); // Display the red 'x' icon
                        }

                        // Check if all rows are validated to enable the submit button
                        var allValidated = true;
                        $('table tr').each(function () {
                            let validationElement = $(this).find('.validation-cell');
                            if (validationElement.find('.fa-times-circle').length > 0) {
                                allValidated = false;
                                return false; // exit loop early
                            }
                        });

                        // Disable or enable the submit button based on validation
                        $('#sbmt-btn').attr('disabled', !allValidated);
                    });
                });
            },
            error: function (res) {
                unspark();
                console.log(res);
            }
        });
    });
});


                        // submit the marks 
                        // console.log('preImportMarksRequest:', preImportMarksRequest);
                      // Store marks when the submit button is clicked
    $('#sbmt-btn').click(function () {
        // Disable the button to prevent multiple clicks during the AJAX call
        $(this).attr('disabled', true);

       


        // Prepare the data to be sent to the server
        let marksData = {
            marks: []
        };

        // Loop through each row and extract marks data
        $('table tr').each(function () {

            let marksElement = $(this).find('.marks');
            let studentId = marksElement.data('student-id');
            let fullName = $(this).find('.full-name').text();
            let academicYear = marksElement.data('academic-year');
            let semester = marksElement.data('semester');
            let classId = marksElement.data('class-id');
            let streamId = marksElement.data('stream-id');
            let subjectId = marksElement.data('subject-id');
            let examType = marksElement.data('exam-type');
            let gp = marksElement.data('grading-profile');
            let uuid = marksElement.data('uuid');
            let sp = marksElement.data('sp');
            let marksValue = marksElement.val();

            marksData.marks.push({
                student_id: studentId,
                full_name:fullName,
                academic_year: academicYear,
                semester: semester,
                class_id: classId,
                stream_id: streamId,
                subject_id: subjectId,
                exam_type: examType,
                uuid: uuid,
                gp:gp,
                sp: sp,
                marks: marksValue
            });
        });

        $.ajax({
            type: 'POST',
            url: '{{ route('results.sytem.excel.store') }}',
            data: marksData,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function (response) {
                
                $('#sbmt-btn').attr('disabled', true);
                $('#academic_modal').modal('hide');

                // Handle the response from the server
                if (response.state === 'done') {
                    $('#PrimaryModalalert').modal('show')
                    alert('Marks stored successfully!');
                } else {
                    // Marks storage failed
                    $('#PrimaryModalalert').modal('show')
                    $('.failed').text(res.failed_count)
                    alert('Marks storage failed!');
                }
            },
            error: function (error) {
                $('#sbmt-btn').attr('disabled', false);

                // Handle the error
                console.error(error);
                alert('An error occurred while storing marks.');
            }
        });
    });






                $('#importBtn_og').click(function(){

                    spark()

                    let form_data = new FormData($('#fileUploadForm')[0]);

                        $.ajax({

                        processData: false,
                        contentType: false,
                        type:'POST',
                        data:form_data,
                        url:'{{ route('results.sytem.excel.import.preview')  }}',

                        beforeSend: function(xhr) {

                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                        },

                        success:function(res){
                        unspark();

                        $('#preview_excel').html(res)
                        $('#academic_modal').modal('show');



                        // Loop through the data and validate each row
                        $('table tr').each(function () {
                            let marksElement = $(this).find('.marks');
                            let validationElement = $(this).find('.validation-cell');

                            if (marksElement.length > 0) {
                                let marks = marksElement.val().trim();

                                if (marks !== '' && (marks.match(/^[0-9]+$/) || marks === 'x' || marks === 's')) {
                                    // console.log('hapa hapa');
                                    validationElement.html('<i class="fa-solid fa-circle-check"></i>');
                                } else {
                                    // console.log('fail');
                                    validationElement.html('<i class="fa fa-x-circle"></i>');
                                }
                            } else {
                                validationElement.html('<i class="fa fa-x-circle"></i>');
                            }
                        });

                        // Loop through the data and validate each row
                        // $('table tr').each(function() {

                            // console.log($(this));
                            // return;

                        // let  marks = $(this).find('.marks').val();

                        // console.log('marks',marks);

                        // if (marks.match(/^[0-9]+$/) || marks === 'x' || marks === 's') {
                        //     $(this).find('.validation').html('<i class="fa fa-tick-circle"></i>');
                        // } else {
                        //     $(this).find('.validation').html('<i class="fa fa-x-circle"></i>');
                        // }

                            // let marksElement = $(this).find('.marks');

                            // if(marksElement.length>0)
                            // {
                            //     let marks = marksElement.val();
                                // if(marks.match(/^[0-9]+$/) || marks === 'x' || marks === 's')
                                // {
                            //     if (marks.trim() !== '' && (marks.match(/^[0-9]+$/) || marks === 'x' || marks === 's')) {
                            //         $(this).find('.validation').html('<i class="fa fa-tick-circle"></i>');
                            //     }else{
                            //         $(this).find('.validation').html('<i class="fa fa-x-circle"></i>');
                            //     }
                            // }
                            // else
                            // {
                            //     $(this).find('.validation').html('<i class="fa fa-x-circle"></i>');
                                // console.error("Element with class 'marks' not found in current row");
                        //     }
                        // });

                        // Check if all rows are validated to enable the submit button
                        var allValidated = true;
                        $('table tr').each(function() {
                        if ($(this).find('.validation').html() === '<i class="fa fa-x-circle"></i>') {
                        allValidated = false;
                        return false; // exit loop early
                        }
                        });

                        if (allValidated) {
                        $('#submitBtn').prop('disabled', false);
                        } else {
                        $('#submitBtn').prop('disabled', true);
                        }


                        // $('#stream').html(res.streams)
                        // $('#subjects').html(res.subjects)
                        },

                        error: function(res){
                        unspark();
                        console.log(res)

                        }

                        })

                })



                $('#fileUploadForm').ajaxForm({
                    beforeSend: function () {
                        var percentage = '0';
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        var percentage = percentComplete;
                        $('#progress_count').text(percentage+'%');
                        $('.progress .progress-bar').css("width", percentage+'%', function() {
                          return $(this).attr("aria-valuenow", percentage) + "%";
                        })
                    },
                    complete: function (xhr) {

                        toast.success('Success message', 'Success');


                        audioElement.muted = false;
                        audioElement.play();


                        $.ajax({

                            url:'{{route('results.sytem.excel.incomplete.marks')  }}',

                            success:function(res){
                                $('.incomplete').text(res)
                                setTimeout(function() {
                        $("#icon_span").removeClass('span-icon');
                        playSound();
                    }, 2000);
                            },
                            error:function(res){
                                console.log(res)
                            }
                        })


                        $('.incomplete').text()

                        $('.custom-container').removeClass('d-none')
                        console.log(xhr.responseJSON)
                        if (xhr.responseJSON.success) {
                            $('#download_failed').removeClass('d-none')
                        }

                        if (xhr.responseJSON.failed) {
                            $('#view').removeClass('d-none')
                        }

                        $('#fail_div').text(xhr.responseJSON.failed);
                        $('#pass_div').text(xhr.responseJSON.success);
                    }
                });
            });
        });


  /* END UPLOAD WITH PROGRESS BAR */


let selected_elements = [];
let file_attach_elem = document.getElementById('inputFile');
file_attach_elem.addEventListener('change',function(event){

    const allowedAttachments = ["xls","xlsx","csv"];
    const file = event.target.files[0];
    const fileName = file.name;
    const fileExtension = fileName.substr(fileName.lastIndexOf('.')+1);

    if (!allowedAttachments.includes(fileExtension)) {

        $('#file_attach_err').text(`The selected file type is not allowed. Please select a file with one of the following extensions: ${allowedAttachments.join(", ")}`);
        $('#importBtn').attr('disabled',true);

        }
        else {
            let is_selected = event.target.id;
            checkIfSelected(is_selected);

            if ($('#year').val()  && $('#term').val() && $('#class_sbmt').val() && $('#subjects_sbmt').val() && $('#exams_sbmt').val() && $('#subjects_sbmt').val() ) {
                $('#importBtn').removeAttr('disabled')
            }

            $('#file_attach_err').text('');

            }
});




function countBlank() {
    let blank = parseFloat(0);
    let elements = $('.marks');
    $.each(elements, function (idx, elem) {
        if (elem.value == '') {
            ++blank;
        }
    });

    if (!blank) {
        $('#sbmt-btn').prop('disabled', false);
    } else {
        $('#sbmt-btn').prop('disabled', true);
    }

    $('#blank').text(blank);
}




function checkIfSelected(element){

      if (!selected_elements.includes(element)) {

          selected_elements.push(element);
          }


          if(element == 'year'){
              var index = selected_elements.indexOf('year');
                  if (index !== -1) {
                      selected_elements.splice(index, 1);
                  }
          }

          if(element == 'term'){
              var index = selected_elements.indexOf('term');
                  if (index > -1) {
                      selected_elements.splice(index, 1);
                  }
          }

          if(element == 'class_sbmt'){
              var index = selected_elements.indexOf('class_sbmt');
                  if (index > -1) {
                      selected_elements.splice(index, 1);
                  }
          }

          if(element == 'subjects_sbmt'){
              var index = selected_elements.indexOf('subjects_sbmt');
                  if (index > -1) {
                      selected_elements.splice(index, 1);
                  }
          }

          if(element == 'exams_sbmt'){
              var index = selected_elements.indexOf('exams_sbmt');
                  if (index > -1) {
                      selected_elements.splice(index, 1);
                  }
          }


          if(selected_elements.length == 5 )
           {
              $('#importBtn').attr('disabled',false).attr('title','Now You Can Upload');

          }else{

              $('#importBtn').attr('disabled',true);


          }

}





</script>


@endsection
@endsection







