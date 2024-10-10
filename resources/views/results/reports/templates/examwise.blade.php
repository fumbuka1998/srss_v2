let datatable = $('#table').DataTable({
    processing: true,
    serverSide: true,
    type:'POST',

    ajax: {
            url: '{{ route('results.reports.datatable') }}',
            type:'POST',

            beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

            data: {
                // Additional parameters
                class_id: res.class_id,
                exam_type: res.exam_type,
                semester: res.semester,
                stream_id: res.stream_id,
                subject_id: res.subject_id,
                acdmcyear: res.academic_year,
                subjects: res.subjects,
                elevel:res.elevel
                // Add more parameters as needed
            }
        },

    columns:columns,
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
