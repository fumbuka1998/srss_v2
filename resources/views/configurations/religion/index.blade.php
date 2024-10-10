@extends('layout.index')


@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">RELIGION & RELIGION SECTS</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            {{-- <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a> --}}
            <span class="breadcrumb-item active mr-3">Religion & Sects</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<style>

</style>

<div class="row">
    <div class="col-md-6">
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
                            <a href="javascript:void(0)" title="new religion" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;New Religion</a>
                            @endif
                        </div>
                    </div>
                </div>

                <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                          <th class="color">Name</th>
                          <th class="color">Sects</th>
                          <th class="color">Created By</th>
                          <th class="color">Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>


    <div class="col-md-6">
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
                            <a href="javascript:void(0)" title="new Sect" id="add_sect" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;New Religion Sect</a>
                            @endif
                        </div>
                    </div>
                </div>

                <table id="sect-table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                          <th class="color">Name</th>
                          <th class="color">Created By</th>
                          <th class="color">Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>


    </div>



    {{-- Modal --}}


    <div id="sect_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1 mh-bg">
                    <h4 class="modal-title">Add Sect </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="sectform">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" id="name" name="name"  class="form-control" placeholder="">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="chosen-select-single mg-b-20">
                                <label for="">Religion</label>
                                <select name="religion_id" class="form-control select2s" id="religion_id" style="width: 100%">
                                    <option value="">Select</option>
                                    @foreach ($religions as $religion)
                                    <option value="{{ $religion->id }}">{{ $religion->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sect-btn"  class="btn btn-info">Submit</a>
                </div>

            </div>
        </div>
    </div>



    {{-- end --}}



        </div>
    </div>
    </div>



    {{-- Modal --}}


    <div id="religion_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1 mh-bg">
                    <h4 class="modal-title">Add Religion </h4>
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
                                <input type="text" id="religion_name" name="name"  class="form-control" placeholder="eg Muslim">
                                <input type="hidden" name="uuid" id="religion_uuid">
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
        ajax:'{{ route('religion.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
        {data: 'sect', name:'sect'},
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
    let url = "{{ route("religion.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.edit').click(function(){

    spark()
    let uuid = $(this).data('uuid');
    let url = '{{ route('religion.edit') }}'

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
    $('#religion_uuid').val(res.uuid);
    $('#religion_name').val(res.name);

    $('#religion_modal').modal('show');

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


/* SECT STUFF */


$('#add_sect').click(function(){


$('#sect_modal').modal('show');


})


/* SECT DATATABLE */
let sect_datatable = $('#sect-table').DataTable({
        processing: true,
        serverSide: true,
        ajax:'{{ route('religion.sect.datatable') }}',
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
    let url = "{{ route("academic.classes.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
    datatable.draw();
});



$('.edit-sect').click(function(){

    spark()
    let uuid = $(this).data('uuid');
    let url = '{{ route('religion.sect.edit') }}'
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
    $('#name').val(res.name);
    $('#religion_id').removeClass('select2_demo_3').val(res.religion_id).addClass('select2_demo_3').trigger('change');
    $('#sect_modal').modal('show');

    unspark();

    if (res.state == 'done') {
    sect_datatable.draw();
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






/* END SECT DATATABLE */






/* ADD */

$('#add').click(function(){

$('#religion_modal').modal('show');

})





$('#sbmt-btn').click(function(){
spark()
let form_data = new FormData($('#cform')[0]);
let url = '{{ route("religion.store") }}'
let method = "POST"
$.ajax(
        {
        url:url,
        processData: false,
        contentType: false,
        method:method,
        data:form_data,
        beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },
        success:function(res){
            unspark();

            if (res.state == 'done') {

                toast(res.msg,res.type);
                $('#loader-container').show();
                $('#religion_modal').modal('hide')
                datatable.draw();
            }
        },
        error:function(res){
            console.log(res)
            unspark()
        }
    }
        )

})


$('#sect-btn').click(function(){

let form_data = new FormData($('#sectform')[0]);
let url = '{{ route("religion.sect.store") }}'
let method = "POST"

spark()

$.ajax(
        {
        url:url,
        processData: false,
        contentType: false,
        method:method,
        data:form_data,
        beforeSend: function(xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },
        success:function(res){
            unspark();
            if (res.state == 'done') {
                toast(res.msg,res.title);
                $('#loader-container').show();
                $('#sect_modal').modal('hide')
                sect_datatable.draw();
                datatable.draw();
            }
        },
        error:function(res){
            console.log(res)
            unspark()
        }
    }
        )

})










    </script>
@endsection
@endsection







