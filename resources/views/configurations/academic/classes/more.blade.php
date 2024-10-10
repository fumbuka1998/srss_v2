@extends('layout.index')

@section('body')

<style>
    .select2-container {
        min-width: 27rem;
    }
    .chosen-select-single{
        display: flex;
        flex-direction: column;
    }
</style>

    <div class="row">
        <div class="col-md-12">
            <div class="tab-content">
                <div id="inbox" class="tab-pane fade in animated zoomInDown custom-inbox-message shadow-reset active">
                    <div class="mail-title inbox-bt-mg">
                        <h2>Classes</h2>
                        <div class="view-mail-action view-mail-ov-d-n">
                            {{-- <a href="#"><i class="fa fa-reply"></i> Reply</a> --}}
                            <a class="compose-draft-bt" href="javascript:window.print()"><i class="fa fa-print"></i> Print</a>
                            <a href="javascript:void(0)" id="add" class="btn btn-custon-four btn-primary btn-xs"><i class="fa fa-plus"></i>Add</a>
                        </div>
                    </div>
                    <div class="datatable-dashv1-list custom-datatable-overright">
                           <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                            <thead>
                                <tr>
                                  <th>Class</th>
                                  <th>Code</th>
                                  <th>Education Level</th>
                                  <th>Capacity</th>
                                  <th>Created By</th>
                                  <th>Action</th>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" id="name" name="name"  class="form-control" placeholder="eg Form 2">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" id="code"  class="form-control" placeholder="">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="chosen-select-single mg-b-20">
                                <label for="">Education Level</label>
                                <select name="education_level_id" class="form-control select2_demo_3" id="education_level_id">
                                    <option value="">Select</option>
                                    @foreach ($education_levels as $elevel )
                                    <option value="{{ $elevel->id }}">{{ $elevel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Capacity</label>
                                <input type="text" name="capacity" id="capacity"  class="form-control" placeholder="">
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







