@extends('layout.index')

@section('body')

<style>
    .radios-flex{
        display: flex;
        justify-content: space-evenly
    }



</style>


<div class="inbox-mailbox-area mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    @include('results.nav')
                    <div class="col-lg-9">
                        <div class="tab-content">
                            <div id="viewmail" class="tab-pane fade in animated zoomInDown shadow-reset custom-inbox-message active">
                                <div class="view-mail-wrap">
                                    <div class="mail-title">
                                        <h2>Marks Entry</h2>
                                    </div>
                                    <div class="view-mail-content">
                                        <div class="form-group-inner">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="pull-left radios-flex">
                                                        <div class="row" style="margin-right: 0.5rem">
                                                            <div class="col-lg-12">
                                                                <div class="i-checks pull-left">
                                                                    <label>
                                                                        <input type="radio" {{ $activeRadio == 'template' ? 'checked' : '' }} value="template" name="a"> <i></i> By Template </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="i-checks pull-left">
                                                                    <label>
                                                                            <input type="radio" value="system" {{ $activeRadio == 'system' ? 'checked' : '' }} name="a"> <i></i> By System </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    <div class="row" style="margin-top: 4rem">

                                        <fieldset style="border: 2px solid #b6babb; border-radius: 1rem; min-height: 40rem;">
                                            <div class="row" style="margin: 0.5rem 1rem">


                                                <div class="col-md-3">
                                                    <div class="chosen-select-single mg-b-20">
                                                        <label for="">Academic Year</label>
                                                        <select name="year" class="form-control select2_demo_3" id="year">
                                                            <option value=""></option>
                                                            @foreach ($academic_years as $year )
                                                            <option value="{{$year->id}}">{{$year->name}}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <div class="chosen-select-single mg-b-20">
                                                        <label for="">Term</label>
                                                        <select name="term" class="form-control select2_demo_3" id="term">

                                                        </select>
                                                </div>
                                                </div>


                                            <div class="col-md-3">
                                                <div class="chosen-select-single mg-b-20">
                                                    <label for="">Class</label>
                                                    <select name="class" class="form-control select2_demo_3" id="class">
                                                        <option value=""></option>
                                                        @foreach ($classes as $class )
                                                        <option value="{{$class->id}}">{{$class->name}}</option>
                                                        @endforeach
                                                    </select>
                                            </div>
                                            </div>


                                            <div class="col-md-3">

                                                <div class="chosen-select-single mg-b-20">
                                                    <label for="">Stream/Combination</label>
                                                    <select name="stream" class="form-control select2_demo_3" id="stream">


                                                    </select>

                                            </div>

                                            </div>


                                            <div class="col-md-3">

                                                <div class="chosen-select-single mg-b-20">
                                                    <label for="">Subject</label>
                                                    <select name="subject" class="form-control select2_demo_3" id="subject">
                                                        <option value=""></option>
                                                       @foreach ($subjects as $subject )
                                                        <option value="{{$subject->id}}">{{$subject->name}}</option>
                                                       @endforeach
                                                    </select>

                                            </div>

                                            </div>

                                            <div class="col-md-3">
                                                <div class="chosen-select-single mg-b-20">
                                                    <label for="">Exam Type</label>
                                                    <select name="exam" class="form-control select2_demo_3" id="exam">
                                                        <option value=""></option>
                                                       @foreach ($exams as $exam )
                                                        <option value="{{$exam->id}}">{{$exam->name}}</option>
                                                       @endforeach
                                                    </select>

                                            </div>

                                            </div>



                                            <div class="col-md-12" style="display: flex; justify-content: center; margin-top: 2%;">
                                                <span>
                                                    <button type="button" id="generate" class="btn btn-custon-rounded-four btn-icon btn-primary">
                                                        <i class="fa-solid fa-table"></i> Generate
                                                    </button>
                                                </span>
                                            </div>

                                            </div>


                                            <div class="row" style="margin-top: 2rem">
                                                <div class="col-md-12">
                                                    <div class="card" style="width: 100%">
                                                        <div class="card-header">
                                                            <h4> Class  Exam Type Results Upload</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <div style="display: flex; justify-content:space-between">
                                                                <div>
                                                                     <h4>Note:</h4>
                                                                     <span style="font-size: 2rem; font-weight: bold; color: red;">X</span>  <span >- For Absent</span> &nbsp;&nbsp;&nbsp;  <span style="font-size: 2rem; font-weight: bold; color: red;">S </span> <span>- For Sick</span>
                                                                </div>

                                                                <table style=" width:40rem; " class="table table-bordered">
                                                                    <tr>
                                                                        <tr>
                                                                            <th style="background: #edf2f8">Total Students:</th>
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

                                                            <div class="col-md-12">

                                                                <div class="template_class" style="margin-top: 2%;">

                                                                </div>

                                                            </div>



                                                        </div>
                                                    </div>


                                                </div>

                                            <div class="col-md-9">



                                            </div>


                                            <div class="col-md-3">



                                            </div>

                                            </div>




                                    <div class="bottom">

                                    </div>

                                        </fieldset>

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




@section('scripts')

<script>
/* STORE */


$('input[type="radio"]').on('ifChanged', function(event) {
    var selectedValue = $(this).val();
    if (selectedValue === 'template') {
        window.location.href = '{{ route('results.template.export.index')  }}';
    } else if (selectedValue === 'system') {

        window.location.href = '{{ route('results.sytem.entry.index')  }}';
    }
});


document.addEventListener('DOMContentLoaded', function() {



document.addEventListener('click', function(event) {


    let form_data = new FormData($('#upload')[0]);
    let button = event.target.closest('#sbmt');

    if (button) {
        button.disabled = true
        button.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 1.6rem;"></i> submitting...';

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

                if (res.state == 'done') {
                    // console.log("hapa");
                    // return;

                    // showNotification(res.msg, res.type)

                    console.log('data',res.data)

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


                }else if (res.state == 'fail') {
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
}
        })

    }
});

let presentees_count = parseFloat(0);
let absentees_count = parseFloat(0);
let sick = parseFloat(0);


function calculateAttendance(){

    array.forEach(element => {

    });


}

document.addEventListener('blur', function(event) {
/* MARKS VALIDATION */
if (event.target.classList.contains('marks')) {
    let current = event.target;
    let maxScore = current.max;
    let score = current.value;
    if (score != 'S' && score != 's' && score !='x' && score != 'X')  {
        score = parseFloat(current.value);
        if (isNaN(score)) {
            event.target.value = '';
        return;

    }

    if (score == 'S' || score == 's') {
        sick+=1;
        $('#sick').text(sick);

    }

    if(score =='x' || score == 'X'){
        absentees_count+=1;
        $('#absentees').text(absentees_count);
    }

    if (score > maxScore) {
        $('#presentees').text(presentees_count);
        event.target.value = '';
    }else{
    ++presentees_count;
    $('#presentees').text(presentees_count);

    }


    }


}
},true);



});







/* END STORE */

$('#generate').click(function(){

let class_id = $('#class').val()
let stream_id = $('#stream').val()
let subject_id = $('#subject').val()
let exam = $('#exam').val()
let year = $('#year').val()
let term = $('#term').val()


$.ajax({

url:'{{ route('results.sytem.entry.template.query')  }}',
type:'POST',
data:{
class_id:class_id,
stream_id:stream_id,
subject_id: subject_id,
exam_type: exam,
acdmcyear: year,
semester:term
},

beforeSend: function(xhr) {
loadSpinner();
        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },


success:function(res){


$('.template_class').html(res);
stopSpinner();


},

error:function(res){




}



})



})



$('input[type="radio"]').on('ifChanged', function(event) {
var selectedValue = $(this).val();
if (selectedValue === 'template') {
    window.location.href = '{{ route('results.template.export.index')  }}';
} else if (selectedValue === 'system') {

    window.location.href = '{{ route('results.sytem.entry.index')  }}';
}
});






function checkForValidation(){



}






$(document).ready(function() {

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







