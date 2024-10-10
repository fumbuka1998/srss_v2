@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">SUBJECT TEACHERS</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active mr-3">Subject Teachers</span>
        </div>
    </div>
</div>
@endsection

@section('body')


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
                    <a href="javascript:void(0)" title="new subject teacher" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;Allocate Subject Teacher</a>
                    @endif
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12">
                <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                          <th class="color">Teacher</th>
                          <th class="color">Class</th>
                          <th class="color">Stream</th>
                          <th class="color">Subject</th>
                          <th class="color">Created By</th>
                          <th class="color">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
    </div>




    {{-- Modal --}}


    <div id="academic_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1 mh-bg">
                    <h4 class="modal-title">Add Subject Teacher </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="cform">
                    <div class="row">
                        <div class="col-md-12">
                            {{-- <div class="form-group">
                                <label for="class_id">Class</label>
                                <select name="class_id" style="width: 100%"  class="form-control select2s" id="class_id">
                                    <option value="">Select</option>

                                    @foreach ($classes as $class )

                                    <option value="{{ $class->id }}"> {{ $class->name  }} </option>

                                    @endforeach

                                </select>
                            </div> --}}
                            <div class="form-group">
                                <label for="class_id">Class:</label>
                                <select data-must="1" name="class_id" id="class_id" style="width: 100%" class="class_select select2s form-control form-control-sm" required>
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $class )
                                    <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                                <input type="hidden" name="uuid" id="uuid">
                        </div>

                        <div class="col-md-12">
                            {{-- <div class="form-group">
                                <label>Stream</label>
                                <select name="stream" style="width: 100%"  class="form-control select2s" id="stream_id">
                                    <option value=""></option>
                                    @foreach ($streams as  $stream)
                                    <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="form-group">
                                <label for="stream">Stream / Combination:</label>
                                <select name="stream" id="stream_id" style="width: 100%" class="class_select form-control select2s form-control-sm" required>
                                    <option value="">Select Stream</option>
                                    @foreach ($streams  as  $stream)

                                    <option value="{{ $stream->id }}">{{ $stream->name  }}</option>

                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Teacher</label>
                                <select name="teacher" style="width: 100%" class="form-control select2s" id="teacher">
                                    <option value=""></option>

                                    @foreach ($teachers as $teacher )

                                    <option value="{{ $teacher->teacher_id }}">{{   $teacher->full_name  }}</option>

                                    @endforeach

                                </select>
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Subject</label>
                                <select name="subject" style="width: 100%" class="form-control select2s" id="subject">
                                    <option value=""></option>
                                    @foreach ($subjects as $subject )
                                    <option value="{{ $subject->id }}">{{ $subject->name  }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Academic Year</label>
                                <select name="ac_year" style="width: 100%" class="form-control select2s" id="ac_year">
                                    <option value=""></option>
                                    @foreach ($ac_years as $year )
                                    <option value="{{ $year->id }}">{{ $year->name  }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>


                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn"  class="btn btn-info">Submit</a>
                </div>
            </div>
        </div>
    </div>




    {{-- end --}}


    @section('scripts')



    <script>

   let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,
        scrollX:true,
        responsive:true,

        ajax:'{{ route('academic.subjects.assignment.datatable') }}',
        columns:[
        {data: 'teacher', name:'teacher'},
        {data: 'class', name:'class'},
        {data: 'stream', name:'stream'},
        {data: 'subjects', name:'subjects'},
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
    let url = "{{ route("academic.subjects.assignment.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.edit').click(function(){

    let uuid = $(this).data('uuid');
    spark()
    let url = '{{ route('academic.subjects.assignment.edit') }}'
    $.ajax(

    {
        url:url,
        method:"POST",
        data:{
        uuid:uuid
    },
    beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },
    success:function(res){
    $('#uuid').val(res.uuid);
    $('#teacher').removeClass('select2_demo_3').val(res.teacher_id).addClass('select2_demo_3').trigger('change');
    $('#class_id').removeClass('select2_demo_3').val(res.class_id).addClass('select2_demo_3').trigger('change');
    $('#subject').removeClass('select2_demo_3').val(res.subject_id).addClass('select2_demo_3').trigger('change');
    $('#stream_id').removeClass('select2_demo_3').val(res.stream_id).addClass('select2_demo_3').trigger('change');

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

$('#academic_modal').modal('show');

})

$('#sbmt-btn').click(function(){

let form_data = new FormData($('#cform')[0]);
let url = '{{ route("academic.subjects.assignment.store") }}'
let method = "POST"
ajaxQuery({url:url,method:method,form_data:form_data})

})


$('#class_id').click(function(){

    let id  = $(this).val();

    $.ajax({
        url:'{{ route('academic.subjects.streams.subjects.fetch') }}',
        method:"POST",
        data:{
        id:id
    },
    beforeSend: function(xhr) {
        showLoader();
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },

    success:function(res){
        hideLoader();
        $('#stream_id').html(res.streams_html);
        $('#subjects').html(res.subjects_html);
    },

    error:function(res){
        console.log(res)
    }

    })

});

// fetch streams by class

$('#class_id').change(function(){
    let class_id = $(this).val()
    spark()
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
        unspark()
    $('#stream_id').html(res)

    },
    error:function(res){
        unspark()
        console.log(res)
    }

})

})






    </script>
@endsection
@endsection







