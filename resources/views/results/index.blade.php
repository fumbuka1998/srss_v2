@extends('layout.index')


@section('body')

<style>
    .radios-flex{
        display: flex;
        justify-content: space-evenly
    }

.wizard_btns{
margin-top: 0.3rem;
}
.nav-pills .nav-link.active{

background-color:#04476a !important;
}
.success_bg{
    background-color: #0447 !important;
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
</style>


<div class="sparkline9-list zoomInDown active">
    <div class="sparkline9-hd">
        <div class="main-sparkline9-hd">
            <h1>Results Entry</h1>
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
                                            <input type="radio" {{ $activeRadio == 'template' ? 'checked' : '' }} value="template" name="a"> <i></i> By Template </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="i-checks pull-left">
                                        <label>
                                                <input type="radio" value="system" {{ $activeRadio == 'system' ? 'checked' : '' }} name="a"> <i></i> By System </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>



            <div class="row" style="margin-top: 4rem; display: flex; justify-content: space-around;">

                <div style="max-width: 64rem;">
                    <h3 class="text-center"> <i class="fa-solid fa-file-excel" style="color: cadetblue"></i> Download Template </h3>
                    <fieldset style="border: 2px solid #b6babb; border-radius: 1rem; min-height: 38rem;">

                        <div class="row" style="margin: 0.5rem 1rem">

                        <div class="col-md-12">
                            <div class="chosen-select-single mg-b-20">
                                <label for="">Class</label>
                                <select name="class" class="form-control select2_demo_3" id="class" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach ($classes as $class )
                                    <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        </div>


                        <div class="col-md-12">

                            <div class="chosen-select-single mg-b-20">
                                <label for="">Stream</label>
                                <select name="stream" class="form-control select2_demo_3" id="stream" style="width: 100%;">

                                </select>

                        </div>

                        </div>


                         <div class="col-md-12">

                            <div class="chosen-select-single mg-b-20">
                                <label for="">Subject</label>
                                <select name="subjects" class="form-control select2_demo_3" id="subjects" style="width: 100%;">

                                </select>

                        </div>

                        </div>



                        <div class="col-md-12" style="display: flex; justify-content:end">
                            <span>
                                <a href="javascript:void(0)" onclick="generateFile('excel')" type="button" style="background: #5b7474" class="btn btn-custon-rounded-four btn-icon btn-success">
                                    <i class="fa-solid fa-cloud-arrow-down"></i> Template
                                </a>
                            </span>
                        </div>

                    </div>

                    </fieldset>

                </div>


                <div style="max-width: 64rem;">

                    <h3 class="text-center"> <i class="fa-solid fa-square-poll-vertical" style="color: #008080;"></i> Upload Results </h3>
                    <fieldset style="border: 2px solid #b6babb; border-radius: 1rem; min-height: 25rem;">

                        <div class="row" style="margin: 0.5rem 1rem">


                            <div class="col-md-6">

                                <div class="chosen-select-single mg-b-20">

                                    <label for="">Academic Year</label>
                                    <select name="class" class="form-control select2_demo_3" id="class" style="width: 100%;">
                                        <option value=""></option>
                                        @foreach ($classes as $class )
                                        <option value="{{$class->id}}">{{$class->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            </div>



                            <div class="col-md-6">

                                <div class="chosen-select-single mg-b-20">

                                    <label for="">Term</label>
                                    <select name="class" class="form-control select2_demo_3" id="class" style="width: 100%;">
                                        <option value=""></option>
                                        @foreach ($classes as $class )
                                        <option value="{{$class->id}}">{{$class->name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            </div>



                        <div class="col-md-6">

                            <div class="chosen-select-single mg-b-20">

                                <label for="">Class</label>
                                <select name="class" class="form-control select2_demo_3" id="class" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach ($classes as $class )
                                    <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        </div>


                        <div class="col-md-6">

                            <div class="chosen-select-single mg-b-20">
                                <label for="">Stream</label>
                                <select name="stream" class="form-control select2_demo_3" id="stream" style="width: 100%;">

                                </select>

                        </div>

                        </div>


                         <div class="col-md-6">

                            <div class="chosen-select-single mg-b-20">
                                <label for="">Subject</label>
                                <select name="subjects" class="form-control select2_demo_3" id="subjects" style="width: 100%;">

                                </select>

                        </div>

                        </div>

                        <div class="col-md-6">

                            <div class="chosen-select-single mg-b-20">
                                <label for="">Exam Type</label>
                                <select name="subjects" class="form-control select2_demo_3" id="subjects" style="width: 100%;">

                                </select>

                        </div>

                        </div>


                        <div class="col-md-12">

                            <div class="chosen-select-single mg-b-20">
                                <label for="">File</label>
                                <input type="file" name="excel_upload" id="excel_upload" class="form-control form-control-sm">

                        </div>

                        </div>




<div class="col-md-12" style="display: flex; justify-content:end">
    <span>
        <a href="javascript:void(0)" onclick="generateFile('excel')" type="button" style="background: #008080; color:white" class="btn btn-custon-rounded-four btn-icon">
            <i class="fa-solid fa-cloud-arrow-up"></i> Upload
        </a>
    </span>
</div>





                    </div>

                    </fieldset>

                </div>

        </div>

    </div>

</div>






@section('scripts')

<script>

$('input[type="radio"]').on('ifChanged', function(event) {
    var selectedValue = $(this).val();
    if (selectedValue === 'template') {
        window.location.href = '{{ route('results.template.export.index')  }}';
    } else if (selectedValue === 'system') {

        window.location.href = '{{ route('results.sytem.entry.index')  }}';
    }
});


function generateFile($file_type){

let class_id = $('#class').val();
let stream_id = $('#stream').val();
let subject_id = $('#subjects').val();
let file_type = $file_type;


let url = '{{ route('results.template.export') }}';
url = url+"?file_type="+file_type+"&class_id="+class_id+"&subject_id="+subject_id+"&stream_id="+stream_id;
window.open(url,'_blank');
console.log(url);

}



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




/* from payfeeTz */






</script>

@endsection



@endsection
