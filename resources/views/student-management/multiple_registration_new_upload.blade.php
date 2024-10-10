@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">STUDENTS REGISTRATION</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            {{-- <a class="breadcrumb-item" href="{{ route('results.reports.generated.reports.index')}}"><i class="icon ion-ios-home-outline"></i> Generated Reports</a> --}}
            <span class="breadcrumb-item active mr-3">Students Registration</span>
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
</style>

<div class="card mt-4">
    <div class="card-header"></div>
    <div class="card-body">
        <div class="row mb-3 ml-2">
            <div class="col-md-4 mg-t-20 mg-lg-t-0">
                <div class="custom-control custom-radio">
                   <input type="radio" {{ $activeRadio == 'single' ? 'checked' : '' }} value="single" name="a" class="custom-control-input" id="radio2">
                   <label class="custom-control-label" for="radio2">Single Registration</label>
                </div>
             </div>
             <div class="col-md-4 mg-t-20 mg-lg-t-0">
                <div class="custom-control custom-radio">
                   <input type="radio" value="multiple" {{ $activeRadio == 'multiple' ? 'checked' : '' }} name="a" class="custom-control-input" id="radio3">
                   <label class="custom-control-label"  for="radio3">Multiple Registration</label>
                </div>
             </div>

        </div>

        @include('student-management.multiple_reg_nav')



                <form method="post" id="upload_form" action="{{ route('students.registration.import') }}"
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="mg-b-20">
                            <label for=""> <i class="fa fa-book" style="color: #069613"></i> Class</label>
                            <select style="width:100%" name="class" class="form-control select2s"
                                id="class_select">
                                <option value="">enter class</option>
                                @foreach ($classes as $class )
                                <option value="{{$class->id}}">{{$class->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="mg-b-20">
                            <label for=""><i class="fa fa-newspaper" style="color: #069613"></i> Stream/Combination</label>
                            <select name="stream" class="form-control select2s" id="stream_select">
                                <option value="">enter stream</option>
                                @foreach ($streams as $stream )
                                <option value="{{$stream->id}}">{{$stream->name}}</option>
                                @endforeach
                            </select>

                        </div>

                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="excelFile"> <i class="fa fa-file-excel" style="color: #069613"></i> Select Excel File</label>
                            <input type="file" class="form-control" id="excelFile" name="file" accept=".xlsx, .xls">
                            <span id="error_msg" class="text-danger"></span>
                        </div>
                    </div>


                    <div class="col-md-3" style="margin-top: 2.4em;">
                        <div class="d-flex align-items-center justify-content-end">
                            <button id="studentUploadBtn" disabled type="button"
                                class="btn btn-info">
                                <span class="fa fa-upload"></span> Preview & Validate
                            </button>
                        </div>
                    </div>

                </div>


            </form>


            <div class="row">
                <div class="col-md-4">
                    <div class="upload results upload_feedback d-none">
                        <aside class="sm-side">
                            <div class="inbox-body">
                               <span class="btn btn-compose" style="cursor:default !important; display:flex; padding-left: 1em"> <img style="height:1.6em;"  src="{{asset('assets/img/3121768.ico')}}" alt=""> <span> &nbsp;Students Upload Summary</span> </span>
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



            {{-- specimen modal start --}}
            <div id="academic_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header header-color-modal bg-color-1">
                            <h4 class="modal-title"> Validate and Submit </h4>
                            <div class="modal-close-area modal-close-df">
                                <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                            </div>
                        </div>
                        <div class="modal-body">
                            <form action="#" id="cform">
                            <div class="row">

                        </form>
                        </div>
                        <div class="modal-footer">
                            <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn btn-sm" href="#">Cancel</a>
                            <a href="javascript:void(0)" id="sbmt-btn"  class="btn btn-info btn-sm">Submit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            {{-- end specimen modal --}}




@section('scripts')

<script>

    $('#studentUploadBtn').click(function(){

        spark()
    let class_id = $(this).val()

    let formdata = new FormData($('#upload_form')[0]);

    $.ajax(

{
    processData: false,
    contentType: false,
    url:'{{ route('students.registration.multiple.pre.excel.import') }}',
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
    $('.failed_href').attr('href','{{ route('students.upload.excel.import.errors') }}');
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


  $('input[type="radio"]').change(function(event) {
    var selectedValue = $(this).val();
    if (selectedValue === 'single') {
        window.location.href = '{{ route('students.registration.single')  }}';
    } else if (selectedValue === 'multiple') {

        window.location.href = '{{ route('students.registration.multiple')  }}';
    }
});


function downloadTemplate(){

  let  url = '{{ route('students.registration.export') }}';
  window.open(url,'_blank');

}

// upload validation

let selected_ids = [];

let file_attach_id = document.getElementById('excelFile');
file_attach_id.addEventListener('change',function(event){

    const allowedExtensions = ["xls","xlsx","csv"];
    const file = event.target.files[0];
    const fileName = file.name;
    const fileExtension = fileName.substr(fileName.lastIndexOf('.')+1);

    if (!allowedExtensions.includes(fileExtension)) {

        $('#error_msg').text(`The selected file type is not allowed. Please select a file with one of the following extensions: ${allowedExtensions.join(", ")}`);
        $('#studentUploadBtn').attr('disabled',true);

        }
        else {
            let is_selected = event.target.id;
            checkIfSelected(is_selected);

            if ($('#class_select').val()  && $('#stream_select').val()) {
                $('#studentUploadBtn').removeAttr('disabled')
            }

            $('#error_msg').text('');

            }
    });


function checkIfSelected(element){

if (!selected_ids.includes(element)) {

    selected_ids.push(element);

    }


    if(element == 'class_select'){
        var index = selected_ids.indexOf('class_select');
            if (index > -1) {
                selected_ids.splice(index, 1);
            }
    }


    if(element == 'stream_select'){
        var index = selected_ids.indexOf('stream_select');
            if (index > -1) {
                selected_ids.splice(index, 1);
            }
    }


    if(selected_ids.length == 2 )
     {
        $('#studentUploadBtn').attr('disabled',false).attr('title','Now You Can Upload');

    }else{

        $('#studentUploadBtn').attr('disabled',true);


    }

}



$('#class_select').change(function(){

    spark()
    let class_id = $(this).val()

    $.ajax(

{
    url:'{{ route('academic.class.streams.fetch') }}',
    method:"POST",
    data:{
        id:class_id
},

beforeSend: function(xhr) {
    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},
success:function(res){

$('#stream_select').html(res)

unspark();

},
error:function(res){
    unspark()
}

})




})

</script>

@endsection





@endsection
