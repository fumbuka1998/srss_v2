@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">SEMESTERS</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active mr-3">Semesters</span>
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
                    <a href="javascript:void(0)" title="new academic year" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;New Semester</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table id="table" class="table compact table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                        <th class="color">Academic Year</th>
                          <th class="color">Semester</th>
                          <th class="color">From</th>
                          <th class="color">To</th>
                          <th class="color">Duration</th>
                          <th class="color">Status</th>
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
                    <h4 class="modal-title">Add Semester </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="acmdform">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="chosen-select-single mg-b-20">
                                <label for="">Academic Year</label>
                                <select name="year" class="form-control select2s" id="year">
                                    @foreach ($years as $year)
                                    <option value="{{$year->id}}">{{$year->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Semester</label>
                                <input type="text" name="name" id="name"  class="form-control form-control-sm" placeholder="eg Semester 1">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start Date</label>
                                    <input type="date" class="form-control" id="from" name="from" value="{{ date('Y-m-d') }}" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>End Date</label>
                                    <input type="date" class="form-control" id="to" min="" name="to" value="{{ date('Y-m-d') }}" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" class="form-control select2s" id="status">
                                    <option value="open">Open</option>
                                    <option value="closed">Closed</option>
                                    <option value="not started">Not Started</option>
                                </select>
                            </div>
                        </div>
                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn" class="btn btn-info ">Submit</a>
                </div>
            </div>
        </div>
    </div>



    {{-- end --}}


    @section('scripts')



    <script>

        $('#from').change(function(){

            let date_val = $(this).val();
            if (date_val > $('#to').val()) {
                $('#to').val(date_val)
            }
            $('#to').attr('min',date_val);

        })

let form = document.getElementById("acmdform");

  let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax:'{{ route('academic.semesters.datatable') }}',
        columns:[
        {data: 'academic_year_id', name:'academic_year_id'},
        {data: 'name', name:'name'},
        {data: 'from', name:'from'},
        {data: 'to', name:'to'},
        {data: 'duration', name:'duration'},
        {data: 'status', name:'status'},
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
    let url = "{{ route("academic.semesters.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.edit').click(function(){

    spark()
    let uuid = $(this).data('uuid');
    let url = '{{ route('academic.semesters.edit') }}'
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

    $('#uuid').val(res.semester.uuid);
    $('#name').val(res.semester.name);
    $('#status').val(res.semester.status).trigger('change');
    $('#year').val(res.semester.academic_year_id).trigger('change');
    $('#from').val(res.from).trigger('change');
    $('#to').val(res.to).trigger('change');

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
    let url = "{{ route("academic.semesters.store") }}"
    let method = "POST"
    let form_data = new FormData($('#acmdform')[0]);
    ajaxQuery({url:url,method:method,form_data:form_data})

}

}),



/* FORM SUBMIT */

$('#sbmt-btn').on('click', function(e){
    e.preventDefault();
    let url = "{{ route("academic.semesters.store") }}"
    let method = "POST"
    let form_data = new FormData($('#acmdform')[0]);
    ajaxQuery({url:url,method:method,form_data:form_data})

});



    </script>
@endsection
@endsection







