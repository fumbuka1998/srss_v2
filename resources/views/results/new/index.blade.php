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
                                        <h2> Waiting For Marking</h2>
                                    </div>
                                    <div class="row">
                                        <input type="hidden" name="xdl_uuid" id="xdl_uuid">
                                        <div class="col-md-12">
                                                    <div class="datatable-dashv1-list custom-datatable-overright">
                                                           <table id="table" class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit">
                                                            <thead>
                                                                <tr>
                                                                  <th>Year</th>
                                                                  <th>Semester</th>
                                                                  <th>Class</th>
                                                                  <th>Stream</th>
                                                                  <th>Exam</th>
                                                                  <th>Subject</th>
                                                                  <th>Marking Closes In</th>
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
                    </div>
        </div>
</div>
    {{-- Modal --}}





    {{-- end --}}


    @section('scripts')


    <script>




   let datatable = $('#table').DataTable({
        processing: true,
        serverSide: true,


        ajax:'{{ route('exams.waiting.marking.datatable') }}',
        columns:[
        {data: 'academic_year_id', name:'academic_year_id'},
        {data: 'semester_id', name:'semester_id'},
        {data: 'classes', name:'classes'},
        {data: 'stream_name', name:'stream_name'},
        {data: 'exam_id', name:'exam_id'},
        {data: 'subject_id', name:'subject_id'},
        {data: 'marking_ends', name:'marking_ends'},
        {data:'action', name:'action', orderable:false, searchable:false}


        ],
        "columnDefs": [
        // { className: " text-right font-weight-bold", "targets": [ 1 ] },
        // { className: "text-blue text-right font-weight-bold", "targets": [ 2 ] },
        // { className: "text-danger text-right font-weight-bold", "targets": [ 3 ] }
      ],

      drawCallback:function(settings){



    $(document).ready(function() {

        $('.enter_marks').click(function(e){

        let uuid =   $(this).data('uuid');
        $('#xdl_uuid').val(uuid)
        })


    $('#table tbody tr').each(function() {
        const row = $(this);
        const el = row.find('.regular_timer');
        const countdownElement = row.find('.clock_time');
        const btn = row.find('.enter_marks');
        const regular = row.find('.fa-regular');
        const rowIdentifier = row.data('row-id');

        const sql_time = el.data('marking-end');
        const endTime = new Date(sql_time);

        function updateCountdown() {
            const currentTime = new Date();
            const timeDiff = endTime - currentTime;

            if (timeDiff <= 0) {
                btn.addClass('d-none');
                regular.removeClass('fa-spin');

                countdownElement.html('<span style="color:#e36e60"> Time expired </span>');
            } else {
                const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

                countdownElement.html(`<span style="color:#008080"> ${days} days </span> <span style="color:#2196f3"> ${hours} hours </span> <span style="color:#36a000"> ${minutes} mins </span> <span style="color:#e36e60"> ${seconds} sec </span>`);
            }
        }

        // Call the function to start the countdown
        updateCountdown();

        // Update the countdown every second
        setInterval(updateCountdown, 1000);
    });
});



    // .text('halaaa');

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


/* RETURN TO INCOMPLETE */

$('.revert').click(function(){

let uuid = $(this).data('uuid');
let url = '{{ route('results.complete.revert') }}';

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

xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},

success:function(res){
$('.completed').text(res)
},
error:function(res){

console.log(res)

}

});

/* end */

/* start */

$.ajax({

url:'{{route('results.sytem.excel.incomplete.marks')  }}',

success:function(res){
$('.incomplete').text(res)
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


/* END */






    </script>
@endsection
@endsection







