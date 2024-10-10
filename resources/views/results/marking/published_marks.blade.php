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
                        <h2>Published Marks</h2>
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
                                  <th>Grade</th>
                                  <th>Education Level</th>
                                  <th>From</th>
                                  <th>To</th>
                                  <th>Points</th>
                                  <th>Remarks</th>
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
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Add Grade </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="acmdform">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name"  class="form-control" placeholder="eg A">
                                <input type="hidden" name="uuid" id="uuid">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="chosen-select-single mg-b-20">
                                <label for="">Education Level</label>
                                <select name="education_level_id" class="form-control select2_demo_3" id="education_level_id">
                                    @foreach ($education_levels as $elevel )
                                    <option value="{{ $elevel->id }}">{{ $elevel->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From</label>
                                <input type="number" name="from" id="from"  class="form-control" placeholder="">
                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To</label>
                                <input type="number" name="to" id="to"  class="form-control" placeholder="">
                            </div>

                        </div>



                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Points</label>
                                <input type="number" name="points" id="points"  class="form-control" placeholder="">
                            </div>

                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Remarks</label>
                                <input type="text" name="remarks" id="remarks"  class="form-control" placeholder="">
                            </div>

                        </div>
                </form>
                </div>
                <div class="modal-footer">
                    <a data-dismiss="modal" style="color:#ffff; background:#6c757d" class="btn btn-sm" href="#">Cancel</a>
                    <a href="javascript:void(0)" id="sbmt-btn" class="btn btn-primary btn-sm">Submit</a>
                </div>
            </div>
        </div>
    </div>



    {{-- end --}}


    @section('scripts')
    <script>

         let form = document.getElementById('acmdform')
    let datatable =    $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax:'{{ route('academic.grades.datatable') }}',
        columns:[
        {data: 'name', name:'name'},
        {data: 'education_level_id', name:'education_level_id'},
        {data: 'from', name:'from'},
        {data: 'to', name:'to'},
        {data: 'points', name:'points'},
        {data: 'remarks', name:'remarks'},
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
    let url = "{{ route("academic.grades.destroy") }}"
    let method = "DELETE"
    ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.edit').click(function(){

    let uuid = $(this).data('uuid');
    let url = '{{ route('academic.grades.edit') }}'
    $.ajax({

        url:url,
        method:"POST",
        data:{
        uuid:uuid
    },
    beforeSend: function(xhr) {
        clearForm(form)
        showLoader();
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },
    success:function(res){

    $('#uuid').val(res.uuid);
    $('#name').val(res.name);
    $('#from').val(res.from).trigger('change');
    $('#education_level_id').val(res.education_level_id).trigger('change');
    $('#to').val(res.to).trigger('change');
    $('#remarks').val(res.remarks).trigger('change');
    $('#points').val(res.points).trigger('change');

    $('#academic_modal').modal('show');

    hideLoader();

    if (res.state == 'done') {
    datatable.draw();

    }
    },
    error:function(res){


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
    let url = "{{ route("academic.grades.store") }}"
    let method = "POST"
    let form_data = new FormData($('#acmdform')[0]);
    ajaxQuery({url:url,method:method,form_data:form_data})

}

}),



/* FORM SUBMIT */

$('#sbmt-btn').on('click', function(e){
    e.preventDefault();
    let url = "{{ route("academic.grades.store") }}"
    let method = "POST"
    let form_data = new FormData($('#acmdform')[0]);
    ajaxQuery({url:url,method:method,form_data:form_data})

});






    </script>
@endsection
@endsection







