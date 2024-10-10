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
    .select2-container {
        min-width: 27rem;
    }
    .chosen-select-single{
        display: flex;
        flex-direction: column;
    }

    .radio_hizi :hover{

        cursor: pointer;

    }
    .datatable-btns{
        float: right;
    }

</style>

<div class="card mt-7">

    <div class="card-header">

    </div>

    <div class="card-body">
        @include('configurations.security.tabs')

        <div class="row">
            <div class="col-md-12 mt-2 mb-4">
                <div class="datatable-btns">
                    <a href="javascript:void(0)" title="excel" onclick="generateFile('excel')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-success btn-sm"> <i class="fa fa-file-excel"></i>&nbsp;Excel</a>
                    <a href="javascript:void(0)" title="pdf" onclick="generateFile('pdf')" style="border-radius: 2px !important; margin-right: 3px !important" class="btn btn-warning btn-sm"><i class="fa fa-print"></i>&nbsp;Pdf</a>
                    <a href="javascript:void(0)" title="new role" id="add" type="button" class=" btn btn-info btn-sm" style="border-radius: 2px !important; margin-right: 3px !important"><i class="fa fa-plus-circle"></i>&nbsp;Add Role</a>
            </div>
            </div>

        </div>

<div class="row">
        <div class="col-md-12">
            <div class="tab-pane custom-inbox-message">
            <div class="admintab-wrap mg-b-40">
                {{-- @include('configurations.security.tabs') --}}
                <div class="tab-content">
                    <div id="TabProject" class="tab-pane in active animated flipInX custon-tab-style1">

                        <div class="tab-content">
                            <div id="inbox" class="tab-pane custom-inbox-message shadow-reset active">
                                <div class="mail-title inbox-bt-mg">
                                    {{-- <h2>Roles</h2> --}}

                                </div>
                                <div class="datatable-dashv1-list custom-datatable-overright">
                                       <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                                        <thead>
                                            <tr>
                                              <th class="color">Name</th>
                                              <th class="color">Description</th>
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
            </div>
        </div>
        </div>
        <div class="col-md-12">

        </div>
        </div>
    </div>
    </div>



    {{-- Modal --}}


    <div id="academic_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1 mh-bg">
                    <h4 class="modal-title">Add Role </h4>
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
                                <input type="text" id="name" name="name"  class="form-control" placeholder="eg Teacher">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>

                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" name="description" id="description"  class="form-control" placeholder="">
                            </div>
                        </div>

                         <div class="custom-control custom-radio mg-t-20 ml-4 mg-lg-t-0">
                            <input name="type" type="radio" value="teacher"  class="custom-control-input radio_hizi type" id="radio1">
                            <label class="custom-control-label" for="radio1">isTeacher</label>
                         </div>

                         <div class="custom-control custom-radio mg-t-20 ml-4 mg-lg-t-0">
                            <input name="type" type="radio" value="parent"  class="custom-control-input radio_hizi type" id="radio2">
                            <label class="custom-control-label" for="radio2">isParent</label>
                         </div>
                                </div>
                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn"  class="btn btn-info ">Submit</a>
                </div>
            </div>
        </div>
    </div>



    {{-- end --}}


    @section('scripts')



    <script>

    let form = document.getElementById('cform')

   let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax:'{{ route('configurations.security.roles.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
        {data: 'description', name:'description'},
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
    let url = "{{ route("configurations.security.roles.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.edit').click(function(){
    spark()

    let uuid = $(this).data('uuid');
    let url = '{{ route('configurations.security.roles.edit') }}'
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
    $('#description').val(res.description);
    $('.type').each(function(type,elem){

        if (elem.value == res.type) {
            console.log(elem.value)
            $(elem).prop('checked',true).trigger('change');
            return;
        }

    })


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
let url = '{{ route("configurations.security.roles.store") }}'
let method = "POST"
ajaxQuery({url:url,method:method,form_data:form_data})

})

    </script>
@endsection
@endsection







