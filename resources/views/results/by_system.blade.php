@extends('layout.index')

@section('body')

<style>
    .radios-flex{
        display: flex;
        justify-content: space-evenly
    }


    .like-button {
            cursor: pointer;
        }
        .like-icon {
            animation: shake 0.8s;
            font-size: 7rem;
            display: flex;
            justify-content: center;
            color: #069613;
        }

        .span-icon{
            display: none;
        }


        @keyframes shake {
    0% {
        transform: rotate(0deg);
    }
    10% {
        transform: rotate(5deg);
    }
    20% {
        transform: rotate(-5deg);
    }
    30% {
        transform: rotate(5deg);
    }
    40% {
        transform: rotate(-5deg);
    }
    50% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(0deg);
    }
}




</style>

<div class="card mt-4">
    <div class="card-header">
        MARKS ENTRACE
    </div>

    <div class="card-body">
      @include('results.nav')


      <div class="row mg-t-10 mt-8">
        <div class="col-md-4">
           <div class="custom-control custom-radio">
              <input name="rdio" type="radio" {{ $activeRadio == 'template' ? 'checked' : '' }} value="template" name="a" class="custom-control-input" id="radio1">
              <label style="cursor: pointer"  class="custom-control-label" for="radio1">By Template</label>
           </div>
        </div>
        <!-- col-3 -->
        <div class="col-md-4 mg-t-20 mg-lg-t-0">

           <div class="custom-control custom-radio">
              <input name="rdio" type="radio" value="system" {{ $activeRadio == 'system' ? 'checked' : '' }} name="a" class="custom-control-input" id="radio2">
              <label style="cursor: pointer" class="custom-control-label" for="radio2">By System</label>
           </div>
        </div>
     </div>


    <div class="row hide-in clearfix" style="margin-top: 4rem">

        {{-- The hidden Inputs --}}

        <input type="hidden" name="year" id="year"  value="{{ $acdmc_year->id }}">
        <input type="hidden" name="marking_to" id="marking_to"  value="{{ $marking_to }}">
        <input type="hidden" name="term" value="{{ $semester->id }}" id="term">
        <input type="hidden" name="grading_profile" id="grading_profile" value="{{ $gradingProfile->id }}">
        <input type="hidden" name="class" id="class_shule" value="{{ $clasxs_id }}">
        <input type="hidden" name="stream" id="stream" value="{{ $stream_id }}">
        <input type="hidden" name="subject" id="subject" value="{{ $subject_id  }}" >
        <input type="hidden" name="exam" id="exam" value="{{ $exam->id }}">
        <input type="hidden" name="sp" id="specific_uuid" value="{{ $specific_uuid }}">
        <input type="hidden" name="this_uuid" id="this_uuid" value="{{ $exam_schedule->uuid }}">
        <input type="hidden" name="grade_group_uuid" id="grade_group_uuid" value="{{ $grade_group_id }}">

<div class="col-md-12">
                <table style="width: 100%;" class="display compact responsive nowrap" style="width: 100%">

                    <tr>
                        <th class="d-none d-sm-table-cell" style="background-color: #f0f0f0">Academic Year</th>
                        <th style="background-color: #f0f0f0">Term</th>
                        <th style="background-color: #f0f0f0">Class</th>
                        <th style="background-color: #f0f0f0">Exam Type</th>
                        <th style="background-color: #f0f0f0">Subject</th>
                    </tr>
                    <tr>
                        <td class="d-none d-sm-table-cell">{{ $acdmc_year->name }}</td>
                        <td>{{ $semester->name  }}</td>
                        <td>{{ $school_class->name  }} - {{ $stream->name  }}</td>
                        <td>{{ $exam->name  }}</td>
                        <td>{{ $subject->name }}</td>
                    </tr>


                </table>
            </div>


            {{-- <audio id="notification-audio"  src="{{ asset('assets/sounds/sound1.ogg') }}" autoplay="false" preload="auto"></audio> --}}

            <div class="col-md-12" style="display: flex; justify-content: end; margin-top: 2%;">
                <span>
                    <button type="button" id="generate" class="btn btn-sm  btn-icon btn-info">
                        <i class="fa-solid fa-spinner"></i> Load Students List
                    </button>
                </span>
            </div>


        </div>


        <div class="row up-to d-none" style="margin-top: 2rem; overflow: scroll; transition: height 2s ease-in-out;" >
            <div class="col-md-12">
                <p> <span> <img style="width: 4%;  height:2.6rem"  src="{{ asset('assets/icons/energy-icon.gif') }}" alt=""> Note: </span> put &nbsp;<span style="font-size: 1rem; font-weight: bold; color: red;"> X</span>  <span >- For Absent</span> &nbsp;&nbsp;&nbsp;  <span style="font-size: 1rem; font-weight: bold; color: red;">S </span> <span>- For Sick</span> </p>

            </div>
<div class="col-md-6 justify-end">
    <table id="compactTable" class="display table table-bordered compact responsive nowrap" style="width: 100%">
        <tr>
            <tr>
                <th style="background: #edf2f8">Total:</th>
                <th style="background: #edf2f8">Present:</th>
                <th style="background: #edf2f8">Absent:</th>
                <th style="background: #edf2f8"> Sick: </th>
                <th style="background: #edf2f8"> Blank: </th>
            </tr>
            <tr>

                <td id="total_students">0</td>
                <td id="presentees">0</td>
                <td id="absentees">0</td>
                <td id="sick">0</td>
                <td id="blank">0</td>

            </tr>
    </table>

</div>

                        </div>


                        <div class="row template_class mt-2">



                        </div>

                    </div>

                    </div>









@section('scripts')

<script>
/* STORE */


function playSound() {

        let audio = new Audio('{{ asset('assets/sounds/sound1.ogg') }}');
        audio.play();

    }




document.addEventListener('DOMContentLoaded', function() {


document.addEventListener('click', function(event) {


    let form_data = new FormData($('#upload')[0]);
    let button = event.target.closest('#sbmt');
    let draftsbmt = event.target.closest('#draftsbmt');


    if (draftsbmt) {
        draftsbmt.disabled = true
        draftsbmt.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 1.6rem;"></i> submitting...';

        $.ajax({

            processData: false,
            contentType: false,
            url: '{{ route('results.sytem.drafts.entry.store') }}',
            type:'POST',
            data:form_data,
            beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

            success:function(res){

                if (res.state == 'done') {
                    console.log(res);
                    
                    $('#PrimaryModalalert').modal('show')
                    let academic_year_id = res.data.academic_year_id
                    let term_id = res.data.term_id
                    let class_id = res.data.class_id
                    let stream_id = res.data.stream_id
                    let subject_id = res.data.subject_id
                    let exam_id = res.data.exam_id



                    let url = '{{ route('results.reports.index') }}';
                    url = url+"?academic_year_id="+academic_year_id+"&term_id="+term_id+"&class_id="+class_id+"&subject_id="+subject_id+"&exam_id="+exam_id+"&stream_id="+stream_id;

                    console.log(url)

                    $('.success').html(`<span style="color:#5cb85b"> ${res.success_count} </span> <a  id="js_void" href="${url}" class="bt-sm"> <i style="padding-left: 1rem;" class="fa fa-eye">   </i> </a> `)
                    $('.failed').text(res.failed_count)

                    const container = document.querySelector(".up-to");
                    const toggleButton = document.getElementById("toggleButton");

                    $(".up-to").fadeTo(3000, 1,'swing',function(){
                    $('.up-to').slideUp(6000,'swing');
                    });

                    // container.style.height = "0px";
                    // setTimeout(() => {
                    // container.style.display = "none";
                    // }, 2000);

                }else if (res.state == 'fail') {
                    $('#PrimaryModalalert').modal('show')
                    $('.failed').text(res.failed_count)
                    // showNotification(res.msg, res.type)
                }
               draftsbmt.innerHTML = '<i class="fa-solid fa-floppy-disk"></i>  Draft';
               draftsbmt.disabled = false;

            },

            error:function(res){
            showNotification(res.msg, res.type)
            button.innerHTML = 'submit';
            button.disabled = false;
            }
        })



    }




    if (button) {
        spark();
        button.disabled = true
        // button.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 1.6rem;"></i> submitting...';

        $.ajax({

            processData: false,
            contentType: false,
            url: '{{ route('results.sytem.entry.store') }}',
            type:'POST',
            data:form_data,
            beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

            success:function(res){
                $('#sbmt').attr('disabled', true);

                unspark()
                if (res.state == 'done') {
                    toast(res.msg,res.title)
                    console.log(res.data);
                    $('#PrimaryModalalert').modal('show')
                    let academic_year_id = res.data.academic_year_id
                    let term_id = res.data.term_id
                    let class_id = res.data.class_id
                    let stream_id = res.data.stream_id
                    let subject_id = res.data.subject_id
                    let exam_id = res.data.exam_id
                    // let grade_group_id = res.grade_group_id


                    $('.hide-in').addClass('d-none');

                    /* start */

                    $.ajax({

                    url:'{{route('results.sytem.excel.incomplete.marks')  }}',

                    beforeSend: function(xhr) {
                    showLoader();
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },

                    success:function(res){

                    button.disabled = true
                    $('.incomplete').text(res)
                    hideLoader();
                    },
                    error:function(res){
                    console.log(res)
                    }
                    })

                    /* end */

                    setTimeout(function() {
                        $("#icon_span").removeClass('span-icon');
                        playSound();
                    }, 2000);

                    // let url = '{{ route('results.reports.index') }}';
                    // url = url+"?academic_year_id="+academic_year_id+"&term_id="+term_id+"&class_id="+class_id+"&subject_id="+subject_id+"&exam_id="+exam_id+"&stream_id="+stream_id;

                    // console.log(url)

                    // $('.success').html(`<span style="color:#5cb85b"> ${res.success_count} </span> <a  id="js_void" href="${url}" class="bt-sm"> <i style="padding-left: 1rem;" class="fa fa-eye">   </i> </a> `)
                    // $('.failed').text(res.failed_count)


                }else if (res.state == 'fail') {
                    toast(res.msg,res.title)
                    $('#PrimaryModalalert').modal('show')
                    $('.failed').text(res.failed_count)
                    // showNotification(res.msg, res.type)
                }

               button.innerHTML = 'submit';
               button.disabled = false;

            },

            error:function(res){
            showNotification(res.msg, res.type)
            button.innerHTML = 'submit';
            button.disabled = false;
            unspark()
            }
        })

    }
});




    function calculateAttendance(){

        array.forEach(element => {



        });


    }





function countAbsentees(){

    let absent = parseFloat(0);
  let elements = $('.marks');
  $.each(elements, function(idx,elem){

    if (elem.value == 'x') {
        ++absent;
    }

  })

  return absent;

}

function countPresent(){

  let presentees = parseFloat(0);
  let elements = $('.marks');
  $.each(elements, function(idx,elem){

    if (/^\d+$/.test(elem.value)) {
        ++presentees;
    }


  })

  return presentees;


}

function countBlank(){

  let blank = parseFloat(0);
  let elements = $('.marks');
  $.each(elements, function(idx,elem){

    if (elem.value == '') {
        ++blank;
    }


  })

    if (!blank) {
        $('#sbmt').prop('disabled', false);
    } else {
        $('#sbmt').prop('disabled', true);
    }

    $('#blank').text(blank);


}



function countSick(){

  let sick = parseFloat(0);
  let elements = $('.marks');
  $.each(elements, function(idx,elem){

    if (elem.value == 's') {
        ++sick;
    }


  })

  return sick;

}

document.addEventListener('blur', function(event) {

    /* MARKS VALIDATION */
    if (event.target.classList.contains('marks')) {
        let current = event.target;
        let maxScore = parseFloat(current.max);
        let score = current.value.trim();

        if (score != 'S' && score != 's' && score !='x' && score != 'X' && score != '')  {
            score = parseFloat(current.value);
        //     console.log(typeof(score))
        // return;
            if (isNaN(score)) {
                event.target.value = '';
            return;

        }


    }



    if (score == 'S' || score == 's') {

        countBlank();
        $('#sick').text(countSick());


        }else if (score =='x' || score == 'X'){
            countBlank();
            $('#absentees').text(countAbsentees());

        }
        else if (score == ''){
            countBlank();
            $('#presentees').text(countPresent());
            $('#absentees').text(countAbsentees());
            $('#sick').text(countSick());

        }
    else{

    if (score > maxScore) {

    event.target.value = '';
    return;
    }

    countBlank();

    $('#presentees').text(countPresent());


    }


    }
    },true);

});




/* END STORE */

$('#generate').click(function(){
spark()
let class_id = $('#class_shule').val()
let stream_id = $('#stream').val()
let subject_id = $('#subject').val()
let exam = $('#exam').val()
let year = $('#year').val()
let term = $('#term').val()
let grade_group_uuid = $('#grade_group_uuid').val()

let sp = $('#specific_uuid').val()
let uuid = $('#this_uuid').val()

$.ajax({ 

url:'{{ route('results.sytem.entry.template.query')  }}',
type:'POST',
data:{
class_id:class_id,
stream_id:stream_id, 
subject_id: subject_id,
exam_type: exam,
acdmcyear: year,
semester:term,
sp:sp,
uuid:uuid,
grade_group_id:grade_group_uuid
},

beforeSend: function(xhr) {

        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },


success:function(res){

$('.up-to').removeClass('d-none');
$('.template_class').html(res.base_html); 
$('#total_students').html(res.students_count);
$('#blank').text(res.students_count)

unspark();


},

error:function(res){

    unspark();

console.log(res);


}



})



})



$('input[type="radio"]').on('change', function(event) {

let uuid = $('#this_uuid').val();
let subject = $('#subject').val();
let class_shule = $('#class_shule').val()
let stream = $('#stream').val()
let specific_uuid = $('#specific_uuid').val();
let gg = $('#grade_group_uuid').val();

var selectedValue = $(this).val();
if (selectedValue === 'template') {

        let init_url = '{{ route('results.template.export.index',[':uuid',':sp',':gg',':subject',':class',':stream'])  }}';
        let url = init_url.replace(':uuid', uuid).replace(':subject',subject).replace(':class',class_shule).replace(':stream',stream).replace(':sp',specific_uuid).replace(':gg',gg);
        window.location.href = url;

} else if (selectedValue === 'system') {
        let init_url = '{{ route('results.sytem.entry.index',[':uuid',':sp',':gg',':subject',':class',':stream'])  }}';
        let url = init_url.replace(':uuid',uuid).replace(':subject',subject).replace(':class',class_shule).replace(':stream',stream).replace(':sp',specific_uuid).replace(':gg',gg);
        window.location.href = url;
}
});



$(document).ready(function() {

    // console.log('default',getBlankDefault())
    // return

    // $('#blank').text('yeboo');


$('.editable').dblclick(function() {
    $(this).attr('contenteditable', 'true').find('.editable-content').focus();
});

$('.editable .editable-content').blur(function() {
    var $parent = $(this).parent();
    $parent.attr('contenteditable', 'false');
    // You can save the entered marks to a temporary data structure here
});
});



$('#class').change(function(){

let class_id = $(this).val()
$.ajax({

url: '{{ route('links.streams')  }}',
method:'POST',
data:{
    id : class_id
},

beforeSend: function(xhr) {
            showLoader();
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

success:function(res){
    hideLoader();
    $('#stream').html(res.streams).trigger('change');
    $('#subject').html(res.subjects).trigger('change');

},

error: function(){




}


})



});

$('#year').change(function(){

let year_id = $(this).val()

$.ajax({

url: '{{ route('links.terms')  }}',
method:'POST',
data:{
id : year_id
},

beforeSend: function(xhr) {
        showLoader();
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },

success:function(res){
hideLoader();
$('#term').html(res).trigger('change');
},

error: function(){




}


})

})






</script>


@endsection
@endsection







