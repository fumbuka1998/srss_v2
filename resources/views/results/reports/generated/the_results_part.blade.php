
@extends('layout.index')

@section('body')

<style>

.table-container {

overflow-x: scroll;
border: 1px solid #ccc;
position: relative;

}

a.disabled {

    color: #999;
    pointer-events: none;
    text-decoration: none;
    
}


.table {
width: auto;
border-collapse: collapse;
}


.table tr {
background-color: #f9f9f9;
}

.table td, .table th {
padding: 8px;
border: 1px solid #ddd;
}

.table th {
background-color: #069613;
color: #fff;
}


.table-wrapper th:nth-child(-n+3),
.table-wrapper td:nth-child(-n+3) {
position: -webkit-sticky;
position: sticky;
left: 0;
z-index: 1;

}


.comment-textarea {
    width: 100%;
    height: 100px;
    resize: none; /* Disable textarea resizing */
    border: 1px solid #ccc;
    padding: 5px;
    font-size: 14px;
}
.responsive-select-td {
    position: relative;
    width: 100%; /* Adjust the width as needed */
}

.custom-select {
    position: relative;
    width: 100%; /* Adjust the width as needed */
}

#responsive-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    color: #333;
    font-size: 16px;
}

#responsive-select option {
    font-size: 16px;
    background-color: #fff;
    color: #333;
}

@media screen and (max-width: 768px) {
    /* Adjust styles for smaller screens (e.g., mobile devices) */
    #responsive-select {
        font-size: 14px;
    }
}


/* now */

.checkbox-label {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        /* Style for the checkbox input */
        .checkbox-input {
            margin-right: 10px;
            width: 20px;
            height: 20px;
            /* appearance: none; */
            border: 2px solid #333;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            position: relative;

        }

        /* Style for the checkbox when checked */
        .checkbox-input:checked {
            /* background-color: #2196F3; */
            border: 2px solid #2196F3;
        }

        /* Style for the checkbox's checkmark symbol */
        .checkbox-input::after {
            content: "\2713";
            color: white;
            font-size: 18px;
            position: absolute;
            top: 1px;
            left: 4px;
            display: none;
        }

        /* Style for the checkbox label text */
        .checkbox-label-text {
            font-size: 16px;
            color: #333;
        }

        /* Responsive CSS */
        @media (max-width: 600px) {
            .checkbox-input,
            .checkbox-label-text {
                font-size: 14px;
                width: 16px;
                height: 16px;
            }
        }

        .mg-top{
            margin-top: 2rem;
        }

/* end */




</style>


<div class="card mt-4">
    <div class="card-header">The Exam Reports Archive</div>
    <div class="card-body">
        <div class="row">
            <input type="hidden" name="xdl_uuid" id="xdl_uuid">
            <div class="col-md-3 ">
                <div class="form-group">
                    <label for="">Educational Year</label>
                        <select name="acdmy" id="year" class="form-control select2s">
                            <option value="">Select Year......</option>
                            @foreach ($academic_years as $year )
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Semester</label>
                        <select name="term" id="term" class="form-control select2s">
                            <option value="">Select Term......</option>
                        </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Class</label>
                        <select name="class" id="class" class="form-control select2s">
                            <option value="">Select Class......</option>
                            @foreach ($classes as $class )
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Stream</label>
                        <select name="stream" id="stream" class="form-control select2s">

                        </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Report Type</label>
                        <select name="report_type" id="report_type" class="form-control select2s">
                            <option value="">Filter By Report Type...</option>
                            @foreach ($exam_reports as  $report)
                            <option value="{{ $report->id }}">{{  $report->name }}</option>
                            @endforeach
                        </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="">Student</label>
                        <select name="" id="" class="form-control select2s">
                            <option value="">Filter By Student ID</option>
                        </select>
                </div>
            </div>
            <div class="col-md-3 col-sm-12" style="margin-top: 1.8em;">
                <div class="form-group">
                    <span>
                        <button type="button" id="generate" class="btn btn-info">
                            <i class="fa-solid fa-magnifying-glass"></i> &nbsp;Process
                        </button>
                    </span>
                </div>

            </div>

        </div>
    </div>
</div>

                    <div class="bottom"></div>

                    </div>
                            </div>








                {{-- where the results will be displayed --}}

                <div class="row" style="padding-right: 2rem; padding-left: 2rem; padding-bottom:5rem">
                    {{-- <div class="container"> --}}
                        <div class="col-md-12 template_class">

                        </div>
                    {{-- </div> --}}
                </div>



                {{-- end --}}



                {{-- where the info visual presentations / graphs will stay --}}




            </div>
        </div>


    </div>


    </div>


</div>



</div>



@section('scripts')

<script>


/* preview the results apa */

$('#generate').click(function(){

let class_id = $('#class').val()
let stream_id = $('#stream').val()
let subject_id = $('#subject').val()
let exam = $('#exam').val()
let year = $('#year').val()
let term = $('#term').val()
let report_name = $('#report_type').val()
spark();

$.ajax({

url:'{{ route('results.reports.loader.load')  }}',
type:'POST',
data:{
class_id:class_id,
stream_id:stream_id,
subject_id: subject_id,
exam_type: exam,
acdmcyear: year,
report_name:report_name,
semester:term,
},

beforeSend: function(xhr) {

            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },


success:function(res){

    $('.template_class').html(res.html);
    unspark()

},

error:function(res){

unspark()


}



})


})






/* end */










$('#class').change(function(){

spark()

let class_id = $(this).val()
$.ajax({

url: '{{ route('links.streams')  }}',
method:'POST',
data:{
    id : class_id
},

beforeSend: function(xhr) {

            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

success:function(res){

    $('#stream').html(res.streams).trigger('change');

},

error: function(){




}


})

unspark()



});

$('#year').change(function(){
spark()
let year_id = $(this).val()

$.ajax({

url: '{{ route('links.terms')  }}',
method:'POST',
data:{
id : year_id
},

beforeSend: function(xhr) {

        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
        },

success:function(res){

$('#term').html(res).trigger('change');
},

error: function(){




}


})

unspark()

})


</script>


@endsection
@endsection



















