


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


#pdfModal .modal-dialog {
    width: 80vw;
}


#pdfModal .modal-dialog .modal-content {
    height: 100vh;
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

        .checkbox {
    transform: scale(1.5);
}


#pdfFrame{
height: 92vh !important;
}

/* end */




</style>


<div class="row">
    {{-- @include('results.results-nav.index') --}}
    <div class="col-lg-12">
        <div class="tab-content">
            <div style="min-height: 33.5rem;" id="viewmail" class="tab-pane fade in animated zoomInDown shadow-reset custom-inbox-message active">
                <div class="view-mail-wrap">
                    <div class="mail-title">
                        <h2> Generated Reports</h2>
                    </div>


                    <div class="row">
                        <div class="col-lg-12" style="margin-bottom: 5rem">
                        <div style="margin-top:2rem" class="fade in animated zoomInDown active">
                        <div class="row elevation-2" style="padding-right: 2rem; padding-left: 2rem;">
                        <div class="col-md-12" style="margin-bottom: 1rem">
                        <span style="float:right">
                            @if (auth()->user()->hasRole('Teacher') && $generated_exam_report->escalation_level_id == 1)
                            <a href="javascript:void(0)" title="Escalate Report" type="button"  style="color:white" data-uuid="{{ $generated_exam_report->uuid }}"  class="escalate_report btn btn-primary btn-sm"> <i class="fa-solid fa-person-arrow-up-from-line"></i> Escalate to Academic Office </a>
                            @endif

                            @if (auth()->user()->hasRole('Academic') && $generated_exam_report->escalation_level_id == 2 )
                            <a href="javascript:void(0)" title="Escalate Report" type="button"  style="color:white" data-uuid="{{ $generated_exam_report->uuid }}"  class="escalate_report btn btn-primary btn-sm"> <i class="fa-solid fa-person-arrow-up-from-line"></i> Approve & Escalate to HM </a>
                            @endif

                        <a href="javascript:void(0)" title="pdf" id="pdfButton"  style="color:white; background:#377493; font-size:1.5rem"  class="btn btn-sm"><i class="fa fa-print"></i> Print Pdf </a>
                        </span>

                        </div>

                            <div class="col-md-12">

                            <form id="single_exam_type_no_subject">
                            <div class="table-container">
                            <div class="table-wrapper">
                                <input type="hidden" value="{{ encrypt($matokeo)  }}" name="our_token" id="our_token">
                                <table class="table table-bordered" id="results" style="width:100%">
                                    <thead>
                                        <tr>
                                            @php
                                                $colspan = 0;
                                                    foreach ($subjects as $key => $subject){
                                                        $colspan +=1;
                                                    }

                                            @endphp
                                            <th rowspan="3"> <label style="display: flex"> <input type="checkbox"  class="checkbox checkall">  &nbsp;<span>C/U</span>  </label> </th>
                                            <th rowspan="3" class="frozen">SN</th>
                                            <th rowspan="3" class="frozen" style="text-align: center; width:20rem">ADMSN N0.</th>
                                            <th style="min-width:25rem" class="text-center frozen" rowspan="3">FULL NAME</th>
                                            <th style="text-align:center" class="text-center" colspan="{{ $colspan * 2}}">
                                            SUBJECTS  (x/ {{ $examInfo->total_marks }})
                                            </th>
                                            <th style="text-align:center" rowspan="3"> AVG </th>
                                            <th style="text-align:center" rowspan="3"> DIV </th>
                                            <th style="text-align:center" rowspan="3"> POINTS </th>
                                            <th style="text-align:center" rowspan="3"> C/T Comments </th>
                                            @if (auth()->user()->hasRole('Head Master') || $trigger_hm > 0 )
                                            <th style="text-align:center" rowspan="3"> H/M comments </th>
                                            @endif
                                        </tr>
                                        <tr>

                                            @foreach ($subjects as $key => $subject)

                                                <th style="text-align:center" colspan="2" data-subject_uuid="{{ $subject->uuid }}">{{ $subject->sbjct_code }} </th>
                                                @php
                                                    $code  =   strtolower( str_replace(' ','_',$subject->sbjct_name));
                                                    $sbjct_columns[] = [
                                                    'data'=> $subject->sbjct_code,
                                                    'name'=> $code,
                                                    'subject_id'=> $subject->subject_id,
                                                ];

                                                @endphp

                                            @endforeach
                                        </tr>

                                        <tr>


                                            @foreach ($sbjct_columns as $key => $column)

                                                <th style="text-align:center" data-subject_id="{{ $column['subject_id'] }}" class="text-center">Marks</th>
                                                <th style="text-align:center" data-subject_id="{{ $column['subject_id'] }}" class="text-center">Grade</th>

                                                @endforeach

                                            </tr>


                                    </thead>

                                    <tbody>

                                        @foreach ($matokeo as $key=> $tokeo )


@php

$metadata = json_decode($tokeo->metadata);
$results = $metadata->results;

@endphp

                                        <tr>
                                            <style>

                                            </style>
                                            <td><input style="font-size: 20rem" type="checkbox" value="{{ $tokeo->student_id }}" class="checkbox other"></td>
                                            <td>{{ ++$key }}</td>
                                            <td> {{ $tokeo->student_id }} </td>
                                            <td>{{ $tokeo->full_name }}</td>


                                            @foreach ($metadata as $meta )

                                            <td>{{ $meta->score }}</td>
                                            <td>{{ $meta->grade }}</td>

                                            @endforeach
                                            <td>    </td>
                                            <td>    </td>
                                            <td>    </td>
                                            <td style="width: 30rem;">  {{ $tokeo->ct_comments  }}  </td>

                                            {{-- @if (auth()->user()->hasRole('Head Master')) --}}

                                            {{-- @elseif ($tokeo->hm_comments) --}}

                                            <td> {{  $tokeo->hm_comments }} </td>

                                            {{-- @endif --}}

                                        </tr>

                                        @endforeach

{{--
                                        $tbody.='

                                            <td style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center">
                                            '.$score_percent.'
                                            </td>
                                            <td style="text-align:center" data-subject_id="'.$column['subject_id'].'" class="text-center"> '.$grade.'</td>';

                                    }

                                    $tbody.='


                                       <td> </td>
                                       <td> </td>
                                       <td> </td>
                                       <td style="min-width: 24rem;"> <div class="form-group">
                                       <select name="ct_comment['.$student->id.']" class="form-control form-control-sm"> '.$options.'  </select> </div></td>
                                       <td style="min-width: 24rem;"> <select name="hm_comment['.$student->id.']" class="form-control form-control-sm"> '.$options.'  </select> </td>
                                    '; --}}

                                    {{-- '.$tbody.' --}}



                                    </tbody>


                                </table>
                                </div>
                                    </div>
                                </form>
                            </div>

                        </div>



                    </div>



                        </div>

                   <h3 style="text-align: center">RESULTS SUMMARY</h3>

                   <div class="row" style="padding-right: 2rem; padding-left: 2rem; padding-bottom:5rem">
                    <div class="container">
                        <div class="col-md-12">
                           <table class="table" style="width: 100%">

                            <thead>
                                <th>x</th>
                                <th>x</th>
                                <th>x</th>
                                <th>x</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </tbody>

                           </table>

                        </div>
                    </div>
                </div>




                <div class="card-body">

                    <div  class="row">

                        <div class="col-md-7">
                            <div class="chart-container" style="position: relative; height:40vh; width:100vw">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="chart-container" style="position: relative; height:40vh; width:100vw">
                                <canvas id="barChat"></canvas>
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


<script>


/* GRAPHS */

pieChart();
function pieChart(){

const ctx = document.getElementById('barChat');

new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

}


barChart();

function barChart(){

const ctx = document.getElementById('myChart');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

}







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

                    console.log(iframes);

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
$('.checkall').change(function(){

        if($(this).prop('indeterminate')){
            $('#results input[type="checkbox"]').prop('checked', false);

        }else{
            $('#results input[type="checkbox"]').prop('checked', this.checked);
            $('#excelButton').show('slow');
            $('#pdfButton').show('slow');
        }


     });

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

/* CHECK ALL DATATABLE */


</script>




















