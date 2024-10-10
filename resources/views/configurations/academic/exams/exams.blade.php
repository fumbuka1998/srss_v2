@extends('layout.index')

@section('body')

@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">EXAMS</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active mr-3">Exams</span>
        </div>
    </div>
</div>
@endsection 

<style>

</style>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 mt-3 mb-2">
                <div class="float-right">
                    <a href="javascript:void(0)" data-toggle="refresh" class="btn btn-warning btn-sm"><i class="ion-android-refresh"></i></a>
                    <a href="javascript:void(0)" data-toggle="expand" class="btn btn-success btn-sm"><i class="ion-android-expand"></i></a>
                    <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                    <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                    @if (auth()->user()->hasRole('Admin'))
                    <a href="javascript:void(0)" title="new exam category" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;New Exam Category</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">

        <div class="col-md-12">
            <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                <thead>
                    <tr>
                      <th class="color">Type</th>
                      <th class="color">Code</th>
                      <th class="color">Total Marks</th>
                      <th class="color">Passmark</th>
                      <th class="color">Created By</th>
                      <th class="color" style="min-width: 5em">Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
    </div>

</div>

    </div>
    </div>



    {{-- Modal --}}


    <div id="academic_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1" style="background:#17a2b8; color:#ffffff">
                    <h4 class="modal-title">Add Exam </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="examForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Type</label>
                                <input type="text" name="name" id="name"  class="form-control" placeholder="eg TEST 2">
                                <input type="hidden" name="uuid" id="uuid">
                                <input type="hidden" name="grade_group" value="1">

                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" id="code"  class="form-control" placeholder="">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Total Marks</label>
                                <input type="number" name="total_marks" id="total_marks"  class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Passmark</label>
                                <input type="number" id="passmark" name="passmark"  class="form-control" placeholder="">
                            </div>

                        </div>

                        <div class="col-md-4 mg-t-20 mg-lg-t-0">
                            <div class="custom-control custom-checkbox">
                               <input type="checkbox" class="custom-control-input" value="1" name="isCummulative"  id="checkbox">
                               <label class="custom-control-label" for="checkbox">is Cummulative</label>
                            </div>
                         </div>



                         <div class="col-md-4 mg-t-20 mg-lg-t-0">
                            <div class="custom-control custom-checkbox">
                               <input type="checkbox" class="custom-control-input" value="1" name="isdP"  id="isdp">
                               <label class="custom-control-label" for="isdp">is Daily Progress</label>
                            </div>
                         </div>


                    {{-- <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Grading Profile</label>
                            <select style="width: 100%;" class="select2s form-control" name="grade_group" id="subject">
                                <option value=""></option>
                                @foreach ($grade_profiles as $profile )
                                    <option value="{{$profile->id}}">{{ $profile->name }}</option>
                                @endforeach
                            </select>
                        </div>
                </div> --}}
{{--
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Subjects</label>
                            <select style="width: 100%" class="select2s form-control" multiple="multiple" name="subjects[]" id="subject">
                                @foreach ($subjects as  $subject)
                                <option value="{{ $subject->id }}">{{   $subject->name  }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}


                    {{-- <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Classes</label>
                            <select style="width: 100%" class="select2s form-control" multiple="multiple" name="classes[]" id="class">
                                @foreach ($classes as $class )
                                <option value="{{ $class->id }}">{{  $class->name  }}</option>

                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                        </div>
                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn btn-sm" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn" class="btn btn-info btn-sm">Submit</a>
                </div>
            </div>
        </div>
    </div>



    {{-- end --}}


    @section('scripts')



    <script>

        let form = document.getElementById('examForm');

    let datatable =  $('#table').DataTable({
        responsive:true,
        scrollX: true,
        processing: true,
        serverSide: true,
        ajax:'{{ route('academic.exams.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
        {data: 'code', name:'code'},
        {data: 'total_marks', name:'total_marks'},
        {data: 'passmark', name:'passmark'},
        {data: 'created_by', name:'created_by'},
        {data:'action', name:'action', orderable:false, searchable:false}
        ],
        "columnDefs": [
        // { className: " text-right font-weight-bold", "targets": [ 1 ] },
        // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
        // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
      ],



      drawCallback:function(){

$('.delete').click(function(){
    let uuid  = $(this).data('uuid');
    let url = "{{ route("academic.exams.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});



$('.edit').click(function(){

    let uuid = $(this).data('uuid');
    spark()

    let url = '{{ route('academic.exams.edit') }}'
    $.ajax({

        url:url,
        method:"POST",
        data:{
        uuid:uuid
    },
    beforeSend: function(xhr) {
        clearForm(form)
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },
    success:function(res){

    $('#uuid').val(res.uuid);
    $('#name').val(res.name);
    $('#code').val(res.code);

    $('#code').val(res.code);


    let isChecked = res.isCommutative === 1;
    let isDp = res.is_dp === 1;
    $('#isdp').prop('checked',isDp);
    $('#checkbox').prop('checked', isChecked);
    $('#total_marks').val(res.total_marks);
    $('#passmark').val(res.passmark);

    $('#academic_modal').modal('show');

    unspark();

    if (res.state == 'done') {
    datatable.draw();

    }
    },
    error:function(res){

        unspark();

    }

    })




})

}

        });



/* ADD */

$('#add').click(function(){

clearForm(form)
$('#academic_modal').modal('show');

})


$('.enter').keyup(function(e){

if (e.keyCode == 13 ) {
    let url = "{{ route("academic.exams.store") }}"
    let method = "POST"
    let form_data = new FormData($('#acmdform')[0]);
    ajaxQuery({url:url,method:method,form_data:form_data})

}

}),



/* FORM SUBMIT */

$('#sbmt-btn').on('click', function(e){
    e.preventDefault();
    let url = "{{ route("academic.exams.store") }}"
    let method = "POST"
    let form_data = new FormData($('#examForm')[0]);
    ajaxQuery({url:url,method:method,form_data:form_data})

});






    </script>
@endsection
@endsection







