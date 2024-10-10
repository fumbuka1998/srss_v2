@extends('layout.index')
@section('top-bread')
@include('student-management.profile_breadcrumb')
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
    color: #c26565;
}

.user-profile-img img {
border-radius: 0 !important;

}

.checkin:hover{

cursor: pointer !important;

}

.col-border{
    border-right: 1px solid #17a2b8;
    top: 0;
}

.col-border-top{
    border-top: 1px solid #17a2b8;
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
            margin-right: 3em;
            font-weight: bold;
        }

</style>


<div class="card">
    <div class="card-body">
        <div class="row">
            @include('student-management.profile_part')
              <div class="col-md-9">
                  @include('student-management.the_nav')
        <div class="row">

            <div class="col-md-12 mt-4">
                <form id="acmdform" data-uuid="{{ $student->student_uuid  }}">

                    <input type="hidden" name="class_id" id="class_id" value="{{ $student->class_id }}">
                    <input type="hidden" name="stream_id" id="stream_id" value="{{ $student->str_id }}">

                <div class="row">
                    @if (count($subjects))
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($subjects as $key => $subject)

                    @php
                    $checked = false;
                    $count += 1;
                    foreach ($assignedSubjects as $key => $assigned) {

                        if ($assigned->subject_id == $subject->id) {
                            $checked = true;
                            break;
                        }

                    }
                    @endphp


{{-- insert the checkbox here --}}

<div class="col-md-4 mg-t-20 mg-lg-t-0 checkin">
    <div class="custom-control custom-checkbox">
        <span class="checkbox-number">{{ $count }}.</span>
        <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" class="form-check-input custom-control-input" id="customCheck{{ $count }}" {{ $checked ? 'checked' : '' }}>
        <label class="custom-control-label form-check-label" for="customCheck{{$count}}">{{ strtoupper($subject->name)  }}</label>
    </div>
</div>
                    @endforeach

                    @else 

                    <h3 style="text-align: center; color:#c26565">No Subjects Allocated For This Particular Class</h3>
                    @endif

                </div>

            </form>
            </div>

        </div>


        @if (count($subjects))
        <div class="row" style="display: flex; justify-content:end;">
         <button style="margin-top: 2%; margin-left:4rem" class="btn btn-primary btn-sm" id="submit_subject"> Submit </button>
        </div>
        @endif


    </div>
</div>
    </div>
</div>


@section('scripts')
<script>

let form = document.getElementById('acmdform');
let uuid = $('#acmdform').data('uuid');

$('.custom-control-input').change(function (event) {
        let checkbox = $(this);
        if (checkbox.prop('checked')) {
            checkbox.closest('.custom-checkbox').find('.custom-control-label').removeClass('cancelled');
        } else {
            checkbox.closest('.custom-checkbox').find('.custom-control-label').addClass('cancelled');
        }
});




/* FORM SUBMiTION  */

$('#submit_subject').on('click', function(e){
e.preventDefault();
spark()
let _url = "{{ route("students.subjects.assignment.store",':uuid') }}"
let url = _url.replace(':uuid',uuid);
let method = "POST"
let form_data = new FormData($('#acmdform')[0]);

$.ajax(
                {
                url:url,
                processData: false,
                contentType: false,
                method:method,
                data:form_data,
                beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success:function(res){
                    unspark()
                    toast(res.msg,res.title);
                    if (res.state == 'done') {

                        $('.subjects_count').text(res.updated_count)

                    }
                },
                error:function(res){
                    unspark();
                    console.log(res)
                }
            }
                )

/* update count */



/* end update count */

});


/* FORM SUBMITION */


let init_url = '{{ route('students.subjects.assignment.datatable',':uuid') }}';
let url = init_url.replace(':uuid',uuid);

let datatable = $('#table').DataTable({
processing: true,
serverSide: true,
ajax:url,
columns:[
{ data: 'index', name: 'index', orderable: false, searchable: false },
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
let url = "{{ route("academic.streams.destroy") }}"
let method = "DELETE"
ajaxQuery({url:url,method:method,uuid:uuid})
});

$('.edit').click(function(){

let uuid = $(this).data('uuid');
let url = '{{ route('academic.streams.edit') }}'
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
$('#capacity').val(res.capacity);
$('#code').val(res.code);
$('#class').val(res.class_id).trigger('change');
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
