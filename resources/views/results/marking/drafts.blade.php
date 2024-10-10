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

<div class="card">
    <div class="card-header">
        DRAFTS
    </div>

    <div class="card-body">
      @include('results.nav')

      <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
        <thead>
            <tr>
              <th>Academic Year</th>
              <th>Semester</th>
              <th>Class</th>
              <th>Stream</th>
              <th>Exam Type</th>
              <th>Subject</th>
              <th>Action</th>
            </tr>
        </thead>
    </table>

    </div>
</div>

@section('scripts')

<script>

   let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,

        responsive:true,
        scrollX: true,
        language: {
        searchPlaceholder: 'Search...',
        sSearch: ''
        },


        ajax:'{{ route('results.sytem.drafts.entry.datatable') }}',
        columns:[
        {data: 'academic_year_id', name:'academic_year_id'},
        {data: 'semester_id', name:'semester_id'},
        {data: 'class_id', name:'class_id'},
        {data: 'stream_id', name:'stream_id'},
        {data: 'exam_id', name:'exam_id'},
        {data: 'subject_id', name:'subject_id'},
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








    </script>
@endsection
@endsection







