@extends('layout.index')

@section('top-bread')
<div class="pageheader pd-y-25 mt-3 mb-3" style="background-color: white;">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5 pl-3">
            <h1 class="pd-0 mg-0 tx-20 text-overflow new-header">STUDENTS SUBJECTS ALLOCATION</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto pr-4">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('academic.index')}}">Academic</a>
            <span class="breadcrumb-item active">Students Subjects Allocation</span>
        </div>
    </div>
</div>
@endsection


@section('body')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css ">
<style>
 
.image-container {
    /* position: relative; */
    width: 200px; /* Adjust the size as needed */
}

.edit-icon {
    position: absolute;
    font-size: 22px;
    color: #007bff;
    cursor: pointer;
}

.cancelled{
    text-decoration: line-through;
}

.user-profile-img img {
border-radius: 0 !important;

}

.custom-control-input{

    cursor: pointer;
    width: 1.5em;
    height: 1.5em;



}



.custom-control-label {
            padding-left: 5px;
        }

        .custom-control-input:checked + .custom-control-label::before {
            background-color: #069613 !important;
            border-color: #069613 !important;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-label {
            margin-bottom: 0;
            flex-grow: 1;
        }

        .checkbox-number {
            margin-right: 10px;
            font-weight: bold;
        }

</style>


<div class="card">
    <div class="card-body">
        <form id="acmdform">
        <div class="row">
                            <div class="col-md-5">
                                <select name="class_name" id="class_name" class="form-control select2s">
                                    <option value="">Select Class....</option>
                                    @foreach ($classes as $key=> $class )
                                    <option value="{{ $class->id }}"> {{ $class->name }} </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-5" class="form-control">
                                <select name="stream_name" id="stream_id" class="form-control select2s">
                                    <option value="">Select Stream....</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button style="margin-left:4rem" class="btn btn-info btn-sm" id="load_students"> <i class="fa-solid fa-spinner"></i> Load </button>
                            </div>



                        </div>

                    </form>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="display_the_list">

                            
                            </div>
                        </div>


                    </div>


            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>


$('#load_students').click(function(e){
    e.preventDefault();
    spark()
    let class_id  = $('#class_name').val();
    let stream_id = $('#stream_id').val()
$.ajax({
    url:'{{ route('students.subjects.assignment.general.load') }}',
    method:"POST",
    data:{
    class_id:class_id,
    stream_id:stream_id
},
beforeSend: function(xhr) {

    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},

success:function(res){
    unspark()
    $('.display_the_list').html(res.html);
    toast(res.msg,res.title)
    manipulateCheckboxes();

},

error:function(res){
    unspark()
    console.log(res)

}

})


})

$('#class_name').change(function(){
spark()
let id  = $(this).val();
$.ajax({
    url:'{{ route('academic.class.streams.fetch') }}',
    method:"POST",
    data:{
    id:id
},
beforeSend: function(xhr) {

    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},

success:function(res){
    unspark();
    $('#stream_id').html(res);

},

error:function(res){

    unspark();
    console.log(res)

}

})



});

let form = document.getElementById('acmdform');
let uuid = $('#acmdform').data('uuid');

$('.custom-control-input').change(function (event) {

        let checkbox = $(this);
        if (checkbox.prop('checked')) {
            checkbox.closest('.form-check').find('.form-check-label').removeClass('cancelled');
        } else {
            checkbox.closest('.form-check').find('.form-check-label').addClass('cancelled');
        }
});




/* FORM SUBMiTION  */

$('#submit_subject').on('click', function(e){
e.preventDefault();
let _url = "{{ route("students.subjects.assignment.store",':uuid') }}"
let url = _url.replace(':uuid',uuid);
let method = "POST"
let form_data = new FormData($('#acmdform')[0]);
ajaxQuery({url:url,method:method,form_data:form_data})

});


/* FORM SUBMITION */

function manipulateCheckboxes(){

    $(".select-all-checkbox").on("click", function () {
        
        var row = $(this).closest("tr");

        var checkboxes = row.find(".checkuncheck");

        // check all check boxes
        checkboxes.prop("checked", $(this).prop("checked"));

        checkboxes.trigger("change");
    });

$(".checkuncheck").change(function(e) {

let subject_id = e.target.value;
let class_id = $(this).data('class-id');
let stream_id = $(this).data('stream-id');
let student_id = $(this).data('student-id');
let student_uuid = $(this).data('student-uuid')
spark()
let grant = parseInt(0);

if($(this).prop('checked')){
grant = parseInt(1);
}

let uuid = $('#uuid').val();
let url = '{{ route('students.subjects.assignment.general.mono.update')}}';

$.ajax({

url:url,
type:'POST',
data:{
    subject_id : subject_id,
    grant:grant,
    class_id:class_id,
    stream_id:stream_id,
    student_id:student_id
},

beforeSend: function(xhr) {

        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },

success:function(res){
    unspark();
    console.log(res)

},

error:function(res){

    console.log(res)
    unspark()

}

})
});


}


</script>


@endsection



@endsection
