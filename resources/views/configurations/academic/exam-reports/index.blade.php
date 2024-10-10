@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">EXAM REPORTS</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active mr-3">Exam reports</span>
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
                          <th class="color">Name</th>
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
                <div class="modal-header header-color-modal bg-color-1 mh-bg">
                    <h4 class="modal-title">Add Exam Report </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="elevelform">
                        @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name"  class="form-control" placeholder="eg Mid Term">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>
                        </div>
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

  let datatable =  $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax:'{{ route('academic.exam.reports.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
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
    let url = "{{ route("academic.exam.reports.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.edit').click(function(){

    spark()

    let uuid = $(this).data('uuid');
    let url = '{{ route('academic.exam.reports.edit') }}'
    $.ajax({

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
    $('#name').val(res.name);
    $('#code').val(res.code);

    $('#academic_modal').modal('show');

    unspark();

    if (res.state == 'done') {
    datatable.draw();

    }
    },
    error:function(res){

        unspark()

    }

    })




})

}





        });



/* ADD */

$('#add').click(function(){

$('#academic_modal').modal('show');

})



/* FORM SUBMIT */

$('#sbmt-btn').click(function(){

let form_data = new FormData($('#elevelform')[0]);
let url = '{{ route("academic.exam.reports.store") }}'
let method = "POST"
ajaxQuery({url:url,method:method,form_data:form_data})

})

</script>
@endsection

@endsection







