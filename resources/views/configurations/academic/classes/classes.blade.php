@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">CLASSES</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active mr-3">classes</span>
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
                    <a href="javascript:void(0)" title="new class" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;New Class</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table id="table" class="table compact table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                          <th class="color">Class</th>
                          <th class="color">Code</th>
                          <th class="color">Education Level</th>
                          <th class="color">Capacity</th>
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
                    <h4 class="modal-title">Add Class </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="cform">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" id="name" name="name"  class="form-control" placeholder="eg Form 2">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" id="code"  class="form-control" placeholder="">
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Education Level</label>
                                <select name="education_level_id" class="form-control select2s" id="education_level_id">
                                    <option value="">Select</option>
                                    @foreach ($education_levels as $elevel )
                                    <option value="{{ $elevel->id }}">{{ $elevel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Capacity</label>
                                <input type="text" name="capacity" id="capacity"  class="form-control" placeholder="eg 30">
                            </div>

                        </div>
                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn btn-sm" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn"  class="btn btn-info btn-sm">Submit</a>
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
        ajax:'{{ route('academic.classes.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
        {data: 'code', name:'code'},
        {data: 'education_level_id', name:'education_level_id'},
        {data: 'capacity', name:'capacity'},
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
    let url = "{{ route("academic.classes.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.edit').click(function(){

    spark()

    let uuid = $(this).data('uuid');
    let url = '{{ route('academic.classes.edit') }}'
    $.ajax(

    {
        url:url,
        method:"POST",
        data:{
        uuid:uuid
    },
    beforeSend: function(xhr) {
        showLoader();
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },
    success:function(res){
    $('#uuid').val(res.uuid);
    $('#name').val(res.name);
    $('#code').val(res.code);
    $('#education_level_id').removeClass('select2_demo_3').val(res.education_level_id).addClass('select2_demo_3').trigger('change');
    $('#capacity').val(res.capacity)


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
let url = '{{ route("academic.classes.store") }}'
let method = "POST"
ajaxQuery({url:url,method:method,form_data:form_data})

})






    </script>
@endsection
@endsection







