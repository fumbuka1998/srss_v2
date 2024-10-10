@extends('layout.index')

@section('body')

<style>
   .color{
   background:  #069613;
   color: white;
   }
</style>


<div class="card mt-4">
    <div class="card-header">
        INCOMPLETE MARKING
    </div>

    <div class="card-body">
      @include('results.nav')
      <div class="row">
        <div class="col-md-12 mt-4">
            <table id="table" class="table compact table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                <thead>
                    <tr>
                      <th class="color">Academic Year</th>
                      <th class="color">Semester</th>
                      <th class="color">Class</th>
                      <th class="color">Stream</th>
                      <th class="color" style="min-width: 9em">Exam Type</th>
                      <th class="color">Subject</th>
                      <th class="color" style="min-width: 9em">Action</th>
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
        responsive:true,
        scrollX: true,
        pageLength:50,
        language: {
        searchPlaceholder: 'Search...',
        sSearch: ''
        },

        ajax:'{{ route('results.sytem.results.upload.incomplete.datatable') }}',
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


// edit




$('.finalize').click(function(){

    let uuid = $(this).data('uuid');
    let url = '{{ route('results.incomplete.finalize') }}';

    let year_id = $(this).data('acdmc');
    let semester_id = $(this).data('smst');
    let class_id = $(this).data('cls');
    let stream_id = $(this).data('strm');
    let exam_type = $(this).data('extype');
    let subject_id = $(this).data('sbjct_id');

        swal.fire({
        title: 'confirm',
        text: 'Are you sure?',
        type: 'warning',
        confirmButtonText: 'OK'
    }).then(function(result) {
        if (result.value) {
            swal.showLoading();

    $.ajax(

{
    url:url,
    method:"POST",
    data:{
        year_id:year_id,
        semester_id:semester_id,
        class_id:class_id,
        stream_id:stream_id,
        exam_type:exam_type,
        subject_id:subject_id
},
beforeSend: function(xhr) {
    showLoader();
    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},
success:function(res){

hideLoader();

if (res.state == 'done') {


Swal.fire({
          icon: 'success', // 'success', 'error', 'warning', etc.
          title: res.msg,
          showConfirmButton: false,
          timer: 2000, // Automatically close after 2 seconds (similar to Toastr)
          timerProgressBar: true,
          position: 'top-end', // Adjust as needed
          toast: true,
        //   background: '#f8f9fa', // Adjust to match your app's design
          // Other customization options...
        });

        datatable.draw();


/* start */

$.ajax(
{
url:'{{ route('results.sytem.excel.completed.marks') }}',
beforeSend: function(xhr) {
    showLoader();
    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},

success:function(res){
    $('.completed').text(res)
    hideLoader();
},
error:function(res){

console.log(res)

}

});

/* end */

/* start */

$.ajax({

url:'{{route('results.sytem.excel.incomplete.marks')  }}',

beforeSend: function(xhr) {
    showLoader();
    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},

success:function(res){
    $('.incomplete').text(res)
    hideLoader();
},
error:function(res){
    console.log(res)
}
})

/* end */


}
},
error:function(res){
console.log(res)
}

})
        }


    });

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







