
@extends('layout.index')


@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">GENERATED REPORTS INDRIVE</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('results.reports.generated.reports.index')}}"><i class="icon ion-ios-home-outline"></i> Generated Reports</a>
            <span class="breadcrumb-item active mr-3">Generated Reports Indrive</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<style>


#downloadIcon {
    font-size: 2rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-20px);
    }
    60% {
        transform: translateY(-10px);
    }
}

/* Make the first three columns sticky */
/* end */
</style>

<div class="card mt-4">
    <div class="card-body">
    <div class="row clearfix">


        <div class="col-md-12">
            @include('results.reports.generated.indrive_nav')
        </div>

        {{-- <div class="row mg-t-10 mt-8"> --}}
            <div class="col-md-4 mt-4">
               <div class="custom-control custom-radio">
                  <input name="rdio" type="radio" {{ $activeRadio == 'ca' ? 'checked' : '' }} value="template" name="a" class="custom-control-input" id="radio1">
                  <label style="cursor: pointer"  class="custom-control-label" for="radio1">By Template</label>
               </div>
            </div>
            <!-- col-3 -->
            <div class="col-md-4 mg-t-20 mg-lg-t-0 mt-4">

               <div class="custom-control custom-radio">
                  <input name="rdio" type="radio" value="system" {{ $activeRadio == 'system' ? 'checked' : '' }} name="a" class="custom-control-input" id="radio2">
                  <label style="cursor: pointer" class="custom-control-label" for="radio2">By System</label>
               </div>
            </div>
         {{-- </div> --}}


         {{-- <div class="row hide-in clearfix" style="margin-top: 4rem"> --}}
            <div class="col-md-12 mt-4">

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


                            <table class="display compact responsive nowrap" style="width:100%">

                                <tr>
                                    <td style="font-weight:bold"> Class</td>

                                    <td></td>
                                </tr>

                                <tr>
                                    <td>{{ $class_model->name }} - {{ $stream_model->name  }} </td>
                                    <td>  <span>
                                        <a href="javascript:void(0)" id="mba" type="button" style="background: #5cb85b" class="btn btn-sm btn-icon btn-success">
                                            <i class="fa-solid fa-cloud-arrow-down"></i> Download Template
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
                            <i class="fa-solid fa-square-poll-vertical" style="color: #008080;"></i> Upload Character Assessment
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
                                    <th style="background: #39ae53; color:white">Report Type</th>
                                </tr>

                                <tr>
                                    <td>{{ $acdmc_year->name }}</td>
                                    <td>{{ $semester->name }}</td>
                                    <td>{{ $class_model->name }}</td>
                                    <td>{{ $stream_model->name }}</td>
                                    <td>{{ $report_type->name }}</td>

                                </tr>

                                </table>


                            </div>

                            {{-- FORM --}}

                            <form id="upload_form" method="POST"  enctype="multipart/form-data">
                                @csrf

                                <audio id="notification-audio"  src="{{ asset('assets/sounds/sound1.ogg') }}" autoplay="false" preload="auto"></audio>


                                <input type="hidden" name="term" value="{{ $semester->id }}" id="term">
                                <input type="hidden" name="class_sbmt" id="class_sbmt" value="{{ $clasxs_id }}">

                                {{-- <input type="hidden" name="exams_sbmt" id="exams_sbmt" value="{{ $exam->id }}"> --}}
                                <input type="hidden" name="stream_id" id="stream" value="{{ $stream_id }}">
                                <input type="hidden" name="this_uuid" id="this_uuid" value="{{ $uuid }}">
                                <input type="hidden" name="academic_year" id="year" value="{{ $acdmc_year }}">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">File</label>
                                        <input type="file" name="file" id="inputFile" accept=".xls, .xlsx" class="form-control form-control-sm" required>
                                       <span id="file_attach_err" class="text-danger">  </span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="progress">
                                            <div style="background: #f9a11c!important" class="progress-bar progress-bar-striped  progress-bar-animated bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"> <span id="progress_count"  style="color: black; display:flex; position:absolute; left:50%; font-size: 1.2rem;">0%</span> </div>
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

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="upload results upload_feedback d-none">
                                        <aside class="sm-side">
                                            <div class="inbox-body">
                                               <span class="btn btn-compose" style="cursor:default !important; display:flex; padding-left: 1em"> <img style="height:1.6em;"  src="{{asset('assets/img/3121768.ico')}}" alt=""> <span> &nbsp;Character Assessment Upload Summary</span> </span>
                                            </div>
                                            <ul class="inbox-nav inbox-divider">
                                               <li class="active">
                                                  <a href="javascript:void(0)"><i class="fa fa-inbox"></i> Success <span class="badge-success pull-right  mg-t-2-force success-upload" ></span>  </a>
                                               </li>
                                               <li>
                                                  <a href="javascript:void(0)" class="failed_href"><i class="fa fa-external-link"></i> Failed<span class="badge-danger pull-right mg-t-2-force failed-upload"></span> <i style="font-size: 1em; color:#069613" class="fa-solid fa-cloud-arrow-down pull-right d-none" id="downloadIcon"></i></a>
                                               </li>
                                            </ul>
                                         </aside>
                                    </div>
                                </div>
                            </div>
                 </div>


            </div>

                    </div>

                </div>
            </div>
        {{-- </div> --}}

    </div>
    </div>
</div>
</div>
    </div>
 </div>

@section('scripts')

<script>

$(document).ready(function(){
    let uuid = @json($uuid);

$('#mba').click(function(){
let url = '{{ route('character.assessments.excel.template',':id') }}';

window.open(url.replace(':id',uuid),'_blank');
})



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

            if ($(this).val() ) {
                $('#importBtn').removeAttr('disabled')
            }

            $('#file_attach_err').text('');

            }
});


$('#importBtn').click(function(){

spark()
let class_id = $(this).val()

let formdata = new FormData($('#upload_form')[0]);
let ir = '{{ route('character.assessments.excel.import',':uuid') }}';
let url = ir.replace(':uuid',uuid)

$.ajax(

{
processData: false,
contentType: false,
url:url,
method:"POST",
data:formdata,

beforeSend: function(xhr) {

xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));

},

success:function(res){

// $('#stream_select').html(res)
cheers(res.msg,res.title)

$('.upload_feedback').removeClass('d-none');
$('.success-upload').text(res.success)
$('.failed-upload').text(res.failed)
if (parseInt(res.failed) > 0) {
$('.failed_href').attr('href','{{ route('character.assessments.excel.import.errors') }}');
$('#downloadIcon').removeClass('d-none')
}

console.log(res)
unspark();

},

error:function(res){
console.log(res)
unspark()
}


})

})


})







/* END OF AN ATTEMPT */

</script>


@endsection
@endsection



















