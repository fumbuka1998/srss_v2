
@extends('layout.index')


@section('top-bread')
<div class="pageheader pd-y-25 mb-3 mt-3" style="background-color: white">
    <div class="row">
        <div class="ml-3 pd-t-5 pd-b-5">
            <h1 class="pd-0 mg-0 tx-20 text-overflow ml-3 new-header">GENERATED REPORTS INDRIVE</h1>
        </div>
        <div class=" mr-3 breadcrumb pd-0 mg-0 text-right ml-auto">
            <a class="breadcrumb-item" href="{{ route('dashboard')}}"><i class="icon ion-ios-home-outline"></i> Dashboard</a>
            <a class="breadcrumb-item" href="{{ route('results.reports.generated.reports.index')}}"><i class="icon ion-ios-home-outline"></i> Generated Reports</a>
            <span class="breadcrumb-item active mr-3">Generated Reports Indrive</span>
        </div>
    </div>
</div>
@endsection

@section('body')

<style>

.table-container {
overflow-x: scroll; /* Enable horizontal scroll */
position: relative;
}

#pdfFrame {
    height: 92vh !important;
}

.tabloid th {
background-color: #069613; /* Header background color */
color: #fff; /* Header text color */
}
/*
/* Freeze the first 3 columns */
.table-wrapper .frozen{
position: sticky;
left: 0;
z-index: 2;
/* background-color: #fff; */

}

.table-wrapper .frozen-td{
background-color: #fff;
}

/* Make the first three columns sticky */
/* end */

</style>


<div class="card mt-8">
    <div class="card-body">
    <div class="row clearfix">


        <div class="col-md-12">
            @include('results.reports.generated.indrive_nav')
        </div>


        <div class="col-md-12" style="margin-bottom: 1rem">
            <span style="float:right">
                <a href="javascript:void(0)" title="pdf" id="pdfButton"  style="color:white; background:#377493;"  class="btn btn-sm {{ $generated_exam_report->is_published ?? 'd-none'  }} "><i class="fa fa-print"></i> Print Pdf </a>
            </span>
       </div>


       <div class="col-md-12">
        <div class="table-container">
            <div class="table-wrapper">
                <input type="hidden" value="{{ encrypt($matokeo)  }}" name="our_token" id="our_token">
                <table class="table tabloid  table-bordered" id="results" style="width:100%">
                    <thead>

                        <tr>
                            @if ($generated_exam_report->is_published)
                            <th rowspan="3" class="frozen"> <label style="display: flex"> <input type="checkbox"  class="checkbox checkall">  &nbsp;<span>C/U</span>  </label> </th>
                            @endif
                            <th rowspan="3" class="frozen">SN</th>
                            <th rowspan="3" class="frozen" style="text-align: center; width:20rem">ADMISSION NUMBER</th>
                            <th style="min-width:25rem" class="text-center frozen" rowspan="3">FULL NAME</th>
                            <th style="text-align:center" class="text-center" colspan="{{ $colspan }}">
                            SUBJECTS
                            </th>
                            <th style="text-align:center" rowspan="3"> AVG </th>
                            <th style="text-align:center" rowspan="3"> GRD </th>
                            <th style="text-align:center" rowspan="3"> DIV </th>
                            <th style="text-align:center" rowspan="3"> POINTS </th>
                            <th style="text-align:center; min-width: 20em" rowspan="3"> C/T Comments </th>
                            @if (auth()->user()->hasRole('Head Master') || $trigger_hm > 0 )
                            <th style="text-align:center min-width: 20em" rowspan="3"> H/M comments </th>
                            @endif
                            <th style="text-align:center" rowspan="3"> Action </th>
                        </tr>
                        <tr>
                            @for ($i=0; $i<count($sbjct_columns);  $i++)
                            <th style="text-align:center" colspan="{{ $sbjct_columns[$i]['subjct_span'] }}" data-subject_uuid="{{ $sbjct_columns[$i]['subjct_uuid'] }}"> {{ strtoupper($sbjct_columns[$i]['name'])  }} </th>
                            @endfor
                        </tr>

                        <tr>
                                @for ($i=0; $i<count($sbjct_columns);  $i++)
                                @foreach ($exam_type_columns  as $key => $coltype )
                                @if ($sbjct_columns[$i]['subject_id'] == $coltype['subject_id'])
                                <th style="min-width: 6em">{{ $coltype['exam']->code }} / {{ $coltype['exam']->total_marks }}</th>
                                @endif

                                @endforeach
                                <th style="text-align:center; min-width: 5em" data-subject_id="" class="text-center">AVG %</th>
                                <th style="text-align:center" data-subject_id=""  class="text-center">GRADE</th>
                                <th style="text-align:center" data-subject_id=""  class="text-center">POINTS</th>
                                @endfor
                            </tr>
                    </thead>

                    <tbody>
                        @foreach ($matokeo as $key=> $tokeo )

                            @php
                            $metadata = json_decode($tokeo->metadata);
                            $results = $metadata->results;

                            $avg = 0;
                            $checkpoints = 0;

                            @endphp

                        <tr>
                            @if ($generated_exam_report->is_published)
                            <td><input style="font-size: 20rem" type="checkbox" value="{{ $tokeo->student_id }}" class="checkbox other"></td>
                            @endif
                            <td>{{ ++$key }}</td>
                            <td> {{ $metadata->admission_no }} </td>
                            <td class="frozen frozen-td">{{ $metadata->full_name }}</td>

                            @for ($i=0; $i<count($sbjct_columns);  $i++)



                            @foreach ($exam_type as $key => $column)

                            @php
                            $subject_id = $sbjct_columns[$i]['subject_id'];

                                $remarks = '-';
                                $avg = '-';
                                $grade ='-';
                                $score = '-';
                                $points = '-';

                                if (isset($results->$subject_id)) {

                                    $check = $results->$subject_id;


                                    if (isset($check->$column)) {

                                    $score =  $check->$column;

                                    }
                                    $avg = $check->AVG;
                                    $grade = $check->GRADE;
                                    $points = $check->POINT;

                                }
                            @endphp

                            <td> {{ $score }} </td>

                            @endforeach

                            <td> {{ $avg  }} </td>
                            <td> {{ $grade }} </td>
                            <td> {{ $points  }} </td>

                            @endfor

                            <td> {{ isset($metadata->AVG) ? $metadata->AVG : 'N/A'   }}</td>
                            <td> {{ isset($metadata->GRADE) ?  $metadata->GRADE : 'N/A' }} </td>
                            <td> {{ isset($metadata->CODE) ? $metadata->CODE : 'N/A'  }} </td>
                            <td> {{ isset($metadata->POINTS) ? $metadata->POINTS : 'N/A'  }} </td>

                            @if (auth()->user()->id == $tokeo->user_id && $generated_exam_report->escalation_level_id == 1 )
                            <td style="width: 40rem;">
                            <select data-uuid={{ $tokeo->uuid }} data-student_id = '{{ $tokeo->student_id }}' name="ct_comment[{{ $tokeo->student_id }}]" class="form-control select2s ct_comment form-control-sm">
                                <option value=""> Select A Comment..... </option>
                                @foreach ($predefined_comments as $comment )
                                <option value="{{ $comment->id }}" {{  $tokeo->ct_predefined_comment_ids == $comment->id ? 'selected' : ''  }}  > {{ $comment->comment  }} </option>
                                @endforeach
                            </select>
                        </td>

                        @else
                        <td style="width: 40rem;">  {{ $tokeo->ct_comments  }}  </td>
                            @endif

                            @if (auth()->user()->hasRole('Head Master'))
                            <td style="width: 30rem;">
                                <select data-uuid={{ $tokeo->uuid }} data-student_id = '{{ $tokeo->student_id }}' name="ct_comment[{{ $tokeo->student_id }}]" class="form-control select2s ct_comment form-control-sm">
                                    <option value=""> Select A Comment..... </option>
                                    @foreach ($predefined_comments as $comment )
                                    <option value="{{ $comment->id }}" {{  $tokeo->hm_predefined_comment_ids == $comment->id ? 'selected' : ''  }}  > {{ $comment->comment  }} </option>
                                    @endforeach
                                </select>

                            </td>

                            @elseif ($tokeo->hm_comments)

                            <td style="min-width: 20em"> {{  $tokeo->hm_comments }} </td>

                            @endif

                            {{-- <td>
                                @if(isset($printButtons[$tokeo->student_id]))
                                    {!! $printButtons[$tokeo->student_id] !!}
                                @endif
                            </td> --}}
                            <td>
                                @if(isset($printButtons[$tokeo->student_id]))
                                    {!! $printButtons[$tokeo->student_id] !!}
                                @else
                                    DEBUG: No print button for student ID {{ $tokeo->student_id }}
                                @endif
                            </td>

                        </tr>

                        @endforeach

                    </tbody>


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



@section('scripts')

<script>


$('.ct_comment').change(function() {

    spark();
            let selectedValue = $(this).val();
            let studentId = $(this).data('student_id');
            let report_uuid = $(this).data('uuid');

            $.ajax({
                type: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                url: '{{ route('results.reports.escalation.hm.comment') }}',
                data: {
                    student_id: studentId,
                    selected_value: selectedValue,
                    report_uuid : report_uuid
                },
                success: function(response) {

                    console.log(response)

                },
                error: function(xhr, status, error) {
                    console.log(error)
                }
            });
            unspark();

        });


$('.escalate_report').click(function(){
    spark();
let ct_comment = $('.ct_comment').val();
let uuid = $(this).data('uuid');
let signature = $('#include-signature').is(':checked') ? $('#include-signature').val() : 0;

$.ajax({
url: '{{ route('results.reports.escalation.top')  }}',
method:'POST',
data:{
    uuid : uuid,
    ct_comment:ct_comment,
    signature:signature
},

beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

success:function(res){
    $('.escalate_report').addClass('disabled');
    if (res.title) {
        showNotification(res.msg,res.title);
    }


},

error: function(res){

    console.log(res)

}


})

unspark();

})

/* ATTEMPT TO PRINT ALL DAMN MOFAKAS --BEIRUT */
$('#pdfButton').hide();

$('#pdfButton').click(function() {

    let student_ids = [];
    spark();


    $(this).prop('disabled', true);
    $('.other').each(function() {
        let elem = $(this);
        if (elem.prop('checked')) {
            student_ids.push(elem.val());
        }
    });



    let our_token = $('#our_token').val();

    $.ajax({
        url: '{{ route('results.reports.generated.single.exam.report.pdf') }}',
        type: 'POST',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
            },

        data: {
            student_ids: student_ids,
            our_token : our_token
        },
        success: function(res) {
                // let pdfUrl = res.pdf_url;
                let modal = $('<div class="modal" id="pdfModal" tabindex="-1" role="dialog">');
                    // console.log('res',res)
                    let iframes = '';
                    // res.reports.forEach(pdfUrl => {
                      let path =  '{{ asset("reports/temp") }}'+ '/'+ res.reports;
                    //   console.log('path',path);
                        iframes = `<iframe id="pdfFrame" src="${path}" width="100%" height="92vh !important"></iframe>`;
                    // });

                    // console.log(iframes);

                modal.html(`<div class="modal-dialog modal-lg" role="document"> <div class="modal-content"><div class="modal-body"> ${iframes} </div></div> </div>`);

                $('body').append(modal);
                modal.modal('show');

                modal.on('hidden.bs.modal', function () {
                modal.remove();

                });

            //     $('#pdfFrame').on('load', function() {
            //     var contentWindow = this.contentWindow;
            //     if (contentWindow) {
            //     contentWindow.print();

            //     // modal.modal('hide');
            //     // setTimeout(() => {
            //     //     modal.remove();
            //     // }, 200);

            //     }
            // });

                unspark();
    },
    error:function(res){
        unspark();
    }

    });


});


/* END OF AN ATTEMPT */


$('.other').change(function(){

$('.checkall').addClass('minus').prop('indeterminate',true);

if ($('#results input[type="checkbox"]:checked').length === 0) {
    $('.checkall').prop('indeterminate', false);
    $('#excelButton').hide('slow');
    $('#pdfButton').hide('slow');

}else{
    $('#excelButton').show('slow');
    $('#pdfButton').show('slow');
}


})

$('.checkall').change(function(){

if($(this).prop('indeterminate')){
    $('#results input[type="checkbox"]').prop('checked', false);

}else{
    $('#results input[type="checkbox"]').prop('checked', this.checked);
    $('#excelButton').show('slow');
    $('#pdfButton').show('slow');
}


});



</script>


@endsection
@endsection



















