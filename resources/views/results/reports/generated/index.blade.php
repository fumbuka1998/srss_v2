
@extends('layout.index')

@section('body')

<style>

.thead{
background: #069613;
color: white;
}

</style>

<div class="card mt-4">
    <div class="card-header">
        GENERATED REPORTS
    </div>

    <div class="card-body">
    <div class="row clearfix">
        <input type="hidden" name="xdl_uuid" id="xdl_uuid">
        @include('results.results-nav.index')

        <div class="col-md-12">
            <table id="table" class="table table-bordered" style="width: 100%">
                <thead>
                    <tr>
                        <th class="thead">Year</th>
                        <th class="thead">Term</th>
                        <th class="thead">Class</th>
                        <th class="thead">Stream</th>
                        <th class="thead">Report</th>
                        <th class="thead">Status</th>
                        <th class="thead"></th>
                    </tr>
                </thead>

            </table>

                </div>
    </div>
    </div>
</div>

    @section('scripts')

<script>


let datatable = $('#table').DataTable({
    processing: true,
    serverSide: true,
    type:'POST',

    ajax: {
            url: '{{ route('results.reports.generated.reports.datatable') }}',
            type:'POST',

            beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

            data: {
                // Additional parameters
                // class_id: res.class_id,
                // exam_type: res.exam_type,
                // semester: res.semester,
                // stream_id: res.stream_id,
                // subject_id: res.subject_id,
                // acdmcyear: res.academic_year,
                // subjects: res.subjects,
                // elevel:res.elevel
                // Add more parameters as needed
            }
        },

    columns:[
        {data: 'academic_year_id', name:'academic_year_id'},
        {data: 'term_id', name:'term_id'},
        {data: 'class_id', name:'class_id'},
        {data: 'stream_id', name:'stream_id'},
        {data: 'exam_report_id', name:'exam_report_id'},
        {data: 'escalation_level_id', name:'escalation_level_id'},
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




$('.editable').click(function(){


let uuid = $(this).data('uuid');
let url = '{{ route('results.uploads.edit') }}'
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
    console.log('res',res)
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

    </script>
@endsection
@endsection



















