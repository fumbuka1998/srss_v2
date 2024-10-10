
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

<div class="inbox-mailbox-area mg-b-15">
    <div class="container-fluid">
                <div class="row">
                    @include('results.nav')
                    <div class="col-lg-9">
                        <div class="tab-content">
                            <div id="viewmail" class="tab-pane fade in animated zoomInDown shadow-reset custom-inbox-message active">
                                <div class="view-mail-wrap">
                                    <div class="mail-title">
                                        <h2>Draft Marking</h2>
                                    </div>
                                    <div>
                                        <h4 style="text-align: right; padding:2rem; background:#e9eef5"> ACADEMIC YEAR - {{ $year }} | CLASS - {{$class_info}} | SUBJECT -{{ $subject }}  | {{ $semester }} | {{ $examInfo->name }} </h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">

                                            <input type="hidden" name="year_id" id="year_id"  value="{{$year_id}}">
                                            <input type="hidden" name="semester_id" id="semester_id" value="{{$semester_id }}">
                                            <input type="hidden" name="class_id" id="class_id" value="{{ $class_id }}">
                                            <input type="hidden" name="stream_id" id="stream_id" value="{{ $stream_id }}">
                                            <input type="hidden" name="exam_id" id="exam_id" value="{{ $exam_id }}">
                                            <input type="hidden" name="subject_id" id="subject_id" value="{{ $subject_id }}">

                                                    <div class="datatable-dashv1-list custom-datatable-overright">
                                                           <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                                                            <thead>
                                                                <tr>
                                                                    <th>SN</th>
                                                                    <th style="text-align: left;font-size: 12px">ADMISSION NO.</th>
                                                                    <th>FULL NAME</th>
                                                                    <th style="text-align: left;font-size: 12px">MARKS/{{$examInfo->total_marks}}</th>
                                                                    <th style="text-align: left;font-size: 12px">%</th>
                                                                    <th style="text-align: left;font-size: 12px">GRADE</th>
                                                                    <th style="text-align: left;font-size: 12px">REMARKS</th>
                                                                    <th></th>
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
        </div>
</div>

    {{-- Modal --}}


    <div id="academic_modal" class="modal  default-popup-PrimaryModal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header header-color-modal bg-color-1">
                    <h4 class="modal-title">Edit Score </h4>
                    <div class="modal-close-area modal-close-df">
                        <a class="close" data-dismiss="modal" href="#"><i class="fa fa-close"></i></a>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="#" id="cform">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Score</label>
                                <input type="text" class="form-control form-control-sm" name="score" id="score">
                                <input type="hidden" class="form-control form-control-sm" name="uuid" id="uuid">
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


    {{-- end --}}


    @section('scripts')



    <script>




   let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,

    ajax: {
    url: '{{ route('results.sytem.drafts.editable.datatable') }}',
    type:'POST',

    beforeSend: function(xhr) {
    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
    },

    data: {
        // Additional parameters
        class_id: $('#class_id').val(),
        exam_type: $('#exam_id').val(),
        semester: $('#semester_id').val(),
        stream_id: $('#stream_id').val(),
        subject_id: $('#subject_id').val(),
        acdmcyear: $('#year_id').val(),
        subjects: $('#subject_id').val(),

        // Add more parameters as needed
    }
},
        columns:[
        {data: 'sn', name:'sn'},
        {data: 'admission_no', name:'admission_no'},
        {data: 'full_name', name:'full_name'},
        {data: 'score', name:'score'},
        {data: 'percentage', name:'percentage'},
        {data: 'grade', name:'grade'},
        {data: 'remarks', name:'remarks'},
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

let uuid = $(this).data('uuid');

let url = '{{ route('results.sytem.drafts.editable.edit') }}'

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
$('#score').val(res.score);
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

$('#academic_modal').modal('show');

})

$('#sbmt-btn').click(function(){

let form_data = new FormData($('#cform')[0]);
let url = '{{ route("results.drafts.score.update") }}'
let method = "POST"
ajaxQuery({url:url,method:method,form_data:form_data})

})


    </script>
@endsection
@endsection






















