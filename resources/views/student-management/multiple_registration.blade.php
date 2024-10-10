@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white;">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow">Manage Promotion</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <span class="breadcrumb-item active">Manage Promotion</span>
        </div>
    </div>
</div>
@endsection

@section('body')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css ">
<style>
    .radios-flex {
        display: flex;
        justify-content: space-evenly
    }

    .button-drop-style-one {

        margin-top: 5rem;

    }

    @keyframes bounce {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .btn-icon .fa {
        transition: transform 0.3s ease-in-out;
        animation: bounce 1s infinite;
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
                                            <input type="radio" {{ $activeRadio=='single' ? 'checked' : '' }}
                                                value="single" name="a"> <i></i> Single Registration </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="i-checks pull-left">
                                        <label>
                                            <input type="radio" {{ $activeRadio=='multiple' ? 'checked' : '' }}
                                                value="multiple" name="a"> <i></i> Multiple Registration </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 4rem">
                <div style="margin-left: 1rem;
                width: 5.5rem;
                height: 5.5rem;
                background: green;
                border-radius: 50%;
                padding: 1rem;
                text-align: center;
                color: white;
                font-size: 1.5rem;"> step 1</div>
                <fieldset style="border: 2px solid #b6babb; border-radius: 1rem; min-height: 25rem;">
                    <legend style="margin-left: 4rem; max-width: 2px;">
                        <a onclick="downloadTemplate()" class="btn btn-custon-rounded-four btn-icon btn-primary"
                            download>
                            <span class="fa fa-download adminpro-informatio"></span> Download Template
                        </a>
                    </legend>

                    <div style="display: flex; justify-content:center">

                        <div style="margin-left: 1rem;
                width: 5.5rem;
                height: 5.5rem;
                background: #008080;
                border-radius: 50%;
                padding: 1rem;
                text-align: center;
                color: white;
                font-size: 1.5rem;"> step 2
                        </div>

                        <form method="post" id="upload_form" action="{{ route('students.registration.import') }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="chosen-select-single mg-b-20">
                                        <label for="">Class</label>
                                        <select style="width:100%" name="class" class="form-control select2_demo_3"
                                            id="class">
                                            <option value="">enter class</option>
                                            @foreach ($classes as $class )
                                            <option value="{{$class->id}}">{{$class->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-3">

                                    <div class="chosen-select-single mg-b-20">
                                        <label for="">Stream/Combination</label>
                                        <select name="stream" class="form-control select2_demo_3" id="stream">
                                            <option value="">enter stream</option>
                                            @foreach ($streams as $stream )
                                            <option value="{{$stream->id}}">{{$stream->name}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="excelFile">Select Excel File</label>
                                        <input type="file" class="form-control" id="excelFile" name="file"
                                            accept=".xlsx, .xls">
                                        <span id="error_msg" class="text-danger"></span>
                                    </div>
                                </div>

                                {{-- third step appears here --}}






                            </div>
                            {{-- <div style="display: flex; align-items: center;">
                                <div
                                    style="margin-left: 30rem; width: 5.5rem; height: 5.5rem; background: #0A4E4E; border-radius: 50%; padding: 1rem; text-align: center; color: white; font-size: 1.5rem;">
                                    Step 3
                                </div>

                                <div style="margin-left: 1rem;">
                                    <button type="submit" class="btn btn-custon-rounded-four btn-icon btn-success">
                                        <span class="fa fa-upload"></span> Upload Excel
                                    </button>
                                </div>
                            </div> --}}

                            <div class="d-flex align-items-center justify-content-end">
                                <div
                                    style="width: 5.5rem; height: 5.5rem; background: #0A4E4E; border-radius: 50%; padding: 1rem; text-align: center; color: white; font-size: 1.5rem; margin-right: 1rem;">
                                    Step 3
                                </div>

                                <button id="studentUploadBtn" disabled type="submit"
                                    class="btn btn-custon-rounded-four btn-icon btn-success">
                                    <span class="fa fa-upload"></span> Upload Excel
                                </button>
                            </div>


                    </div>
                    </form>


                </fieldset>


                {{-- <div class="button-drop-style-one">
                    <span>

                    </span>


                </div> --}}
            </div>

        </div>
    </div>
</div>


@section('scripts')

<script>
    $('input[type="radio"]').on('ifChanged', function(event) {
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

            if ($('#class').val()  && $('#stream').val()) {
                $('#studentUploadBtn').removeAttr('disabled')
            }

            $('#error_msg').text('');

            }
    });


function checkIfSelected(element){

if (!selected_ids.includes(element)) {

    selected_ids.push(element);

    }


    if(element == 'class'){
        var index = selected_ids.indexOf('class');
            if (index > -1) {
                selected_ids.splice(index, 1);
            }
    }


    if(element == 'stream'){
        var index = selected_ids.indexOf('stream');
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

</script>

@endsection





@endsection
