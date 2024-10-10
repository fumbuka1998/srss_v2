@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white;">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">ROLES, PERMISSIONS & MODULES</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('configurations.security.index')}}">Security</a>
            <span class="breadcrumb-item active mr-3">Roles,Permissions & modules</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<style>
   .datatable-btns{
    float:right;
   }
   table th{
    background: #069613;
    color: white;
   }
</style>

<div class="card">
    <div class="card-header"></div>
    <div class="card-body">
        @include('configurations.security.tabs')
        <div class="row">
            <div class="col-md-12 mt-3 mb-2">
                <div class="datatable-btns">
                    {{-- <span class="float-right"> --}}
                        <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                        <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                        <a href="javascript:void(0)"  id="add" type="button" class=" btn btn-primary btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;Add</a>
                    {{-- </span> --}}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table id="table" class="table compact table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                    <thead>
                        <tr>
                          <th>Name</th>
                          <th>Parent</th>
                          <th>Permissions</th>
                          <th>Created By</th>
                          <th>Action</th>
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
                    <h4 class="modal-title">Add Module </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="cform">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" id="name" name="name"  class="form-control" placeholder="">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="chosen-select-single mg-b-20">
                                <label for="">Parent</label>
                                <select name="parent" class="form-control select2s" id="parent">
                                   @foreach ($modules as $module )
                                    <option value="{{$module->id}}">{{$module->name}}</option>
                                   @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="chosen-select-single mg-b-20">
                                <label for="">Permissions</label>
                                <select name="permissions[]" multiple class="form-control select2s" id="permissions">
                                   @foreach ($permissions as $permission )
                                    <option value="{{$permission->id}}">{{$permission->name}}</option>
                                   @endforeach
                                </select>

                        </div>
                        </div>
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn btn-sm" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn"  class="btn btn-primary btn-sm">Submit</a>
                </div>
            </div>
        </div>
    </div>




    {{-- end --}}


    @section('scripts')



    <script>

        let form = document.getElementById('cform');

   let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax:'{{ route('configurations.security.modules.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
        {data: 'parent', name:'parent'},
        {data: 'permissions', name:'permissions'},
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
    let url = '{{ route('configurations.security.modules.edit') }}'
    $.ajax(

    {
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
    $('#parent').val(res.parent_id).trigger('change');
    let selectedPermissions = res.module_permissions;
    selectedPermissions.forEach(permission => {
     $('#permissions option[value="' + permission.permission_id + '"]').prop('selected', true);
    });
    $('#permissions').trigger('change');

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

$('#sbmt-btn').click(function(){

let form_data = new FormData($('#cform')[0]);
let url = '{{ route("configurations.security.modules.store") }}'
let method = "POST"
ajaxQuery({url:url,method:method,form_data:form_data})

})






    </script>
@endsection
@endsection







