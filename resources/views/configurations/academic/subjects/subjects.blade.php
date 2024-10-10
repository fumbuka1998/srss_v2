@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">SUBJECTS</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active mr-3">Subjects</span>
        </div>
    </div>
</div>
@endsection

@section('body')

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
                    <a href="javascript:void(0)" title="new subject" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;Add Subject</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                          <th class="color">Subject</th>
                          <th class="color">Code</th>
                          <th class="color">Education Level</th>
                          <th class="color">Department</th>
                          <th class="color">Type</th>
                          <th class="color">Points</th>
                          <th class="color">Created By</th>
                          <th class="color" style="min-width:5em">Action</th>
                        </tr>
                    </thead>
                </table>



            </div>
        </div>

    </div>
</div>


    {{-- Modal --}}


    <div id="academic_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1 mh-bg">
                    <h4 class="modal-title">Add Subject </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="acmdform">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name"  class="form-control" placeholder="eg Biology">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code"  id="code"  class="form-control" placeholder="eg. BIOS">
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Educational Level</label>
                                <select style="width: 100%" class="select2s form-control" multiple="multiple" id="elevels" name="elevels[]">
                                    @foreach ($elevels as $level )
                                    <option value="{{ $level->id }}">{{$level->name}}</option>
                                    @endforeach

                                </select>
                            </div>

                            {{-- <div class="chosen-select-single mg-b-20">
                                <label for="">Education Level</label>
                                <select name="" class="form-control select2_demo_3" id="">
                                    <option value="">Select</option>
                                </select>
                            </div> --}}
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Department</label>
                                <select name="department" style="width: 100%" class="form-control select2s" id="department">
                                    @foreach ($departments as $department )
                                    <option value="{{ $department->id }}"> {{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Points</label>
                                <input type="number" name="points" id="points"  class="form-control" placeholder="">
                            </div>
                        </div>

                        <div class="col-md-12">
                                <label for="">Course Type</label>
                                <select name="course_type" style="width: 100%" class="form-control select2s" id="course_type">
                                   <option value="PRINCIPAL">Principal</option>
                                   <option value="SUBSIDIARY">Subsidiary</option>
                                </select>
                        </div>

                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn" class="btn btn-info">Submit</a>
                </div>
            </div>
        </div>
    </div>



    {{-- end --}}


    @section('scripts')



    <script>
let form = document.getElementById('acmdform');

    let datatable =   $('#table').DataTable({
        responsive:true,
        scrollX:true,
        processing: true,
        serverSide: true,
        ajax:'{{ route('academic.subjects.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
        {data: 'code', name:'code'},
        {data: 'education_level_id', name:'education_level_id'},
        {data: 'department_id', name:'departments_id'},
        {data: 'subject_type', name:'subject_type'},
        {data: 'points', name:'points'},
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
    let url = "{{ route("academic.subjects.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});





$('.edit').click(function(){
    spark()
    let uuid = $(this).data('uuid');
    let url = '{{ route('academic.subjects.edit') }}'
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

    $('#uuid').val(res[0].uuid);
    $('#name').val(res[0].name);
    $('#code').val(res[0].code);
    $('#points').val(res[0].points);
    $('#course_type').val(`${res[0].subject_type}`).trigger('change');
    $('#department').val(res[0].department_id).trigger('change');


    let selectedLevels = res[0].subject_education_levels;
    console.log(selectedLevels)
    selectedLevels.forEach(selectedLevel => {
     $('#elevels option[value="' + selectedLevel.education_level_id + '"]').prop('selected', true);
    });
    $('#elevels').trigger('change');

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
    let url = "{{ route("academic.subjects.store") }}"
    let method = "POST"
    let form_data = new FormData($('#acmdform')[0]);
    ajaxQuery({url:url,method:method,form_data:form_data})

}

}),



/* FORM SUBMIT */

$('#sbmt-btn').on('click', function(e){
    e.preventDefault();
    let url = "{{ route("academic.subjects.store") }}"
    let method = "POST"
    let form_data = new FormData($('#acmdform')[0]);
    ajaxQuery({url:url,method:method,form_data:form_data})

});




    </script>
@endsection
@endsection







