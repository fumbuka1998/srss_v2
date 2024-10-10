
@extends('layout.index')

@section('body')

<style>
.s1{
   background:  #069613;
   color: #ffff;
}
.card-header{
    display: flex;
    justify-content: space-between;
}
.scrollable-table {
            overflow-x: auto;
        }

</style>

<div class="card mt-4">

    <div class="card-header">

        <span>COMPLETED MARKING </span>
        <div class="row">
            <div class="col-md-4"> <span>ACADEMIC YEAR</span> - <b><i>{{ $year }}</i></b> </div>
            <div class="col-md-4"> <span>CLASS - </span> <strong><i>{{ $class_info }}</i></strong> </div>
            <div class="col-md-4"> <span>SUBJECT</span>  - <strong><i>{{ $subject }}</i></strong> </div>
            <div class="col-md-4">  <span>SEMESTER </span> - <strong><i>{{ $semester }}</i></strong>  </div>
            <div class="col-md-4"> <span>REPORT</span>  - <strong><i>{{ $examInfo->name }}</i></strong> </div>
        </div>
    </div>

    <div class="card-body">
      @include('results.nav')

     <div class="row clearfix mt-4">

            <input type="hidden" name="year_id" id="year_id"  value="{{$year_id}}">
            <input type="hidden" name="semester_id" id="semester_id" value="{{$semester_id }}">
            <input type="hidden" name="class_id" id="class_id" value="{{ $class_id }}">
            <input type="hidden" name="stream_id" id="stream_id" value="{{ $stream_id }}">
            <input type="hidden" name="exam_id" id="exam_id" value="{{ $exam_id }}">
            <input type="hidden" name="subject_id" id="subject_id" value="{{ $subject_id }}">
            <div class="col-md-12">
                <div class="table-reponsive">
                    <table id="table" class="display table scrollable-table  compact nowrap table-striped table-bordered table-sm"  style="width: 100% !important; table-layout: inherit">
                        <thead>
                            <tr>
                                <th class="s1">SN</th>
                                <th class="s1" style="text-align: left;font-size: 12px">ADMISSION NO.</th>
                                <th class="s1">FULL NAME</th>
                                <th class="s1" style="text-align: left;font-size: 12px">MARKS/{{$examInfo->total_marks}}</th>
                                <th class="s1" style="text-align: left;font-size: 12px">%</th>
                                <th class="s1" style="text-align: left;font-size: 12px">GRADE</th>
                                <th class="s1" style="text-align: left;font-size: 12px">REMARKS</th>
                            </tr>
                        </thead>
                    </table>
                </div>

        </div>
     </div>
    </div>
</div>

    {{-- end --}}


    @section('scripts')

    <script>

   let datatable = $('#table').DataTable({
    responsive:true,
    scrollX: true,

    language: {
        searchPlaceholder: 'Search...',
        sSearch: ''
    },
        processing: true,
        serverSide: true,

    ajax: {
    url: '{{ route('results.sytem.complete.marking.indrive.datatable') }}',
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
        // {data:'action', name:'action', orderable:false, searchable:false}


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

let url = '{{ route('results.incomplete.edit') }}'

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
let url = '{{ route("results.incomplete.update") }}'
let method = "POST"
ajaxQuery({url:url,method:method,form_data:form_data})

})


    </script>
@endsection
@endsection






















