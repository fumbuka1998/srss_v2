


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
                            {{-- <h2> Generated Reports</h2> --}}
                        </div>


                        <div class="row">
                            <div class="col-lg-12" style="margin-bottom: 5rem">
                            <div style="margin-top:2rem" class="fade in animated zoomInDown active">
                            <div class="row elevation-2" style="padding-right: 2rem; padding-left: 2rem;">
                            <div class="col-md-12" style="margin-bottom: 1rem">
                            <span style="float:right">

                            </span>

                            </div>

                                <div class="col-md-12">

                                <form id="single_exam_type_no_subject">
                                <div class="table-container">
                                <div class="table-wrapper">
                                    <input type="hidden" value="" name="our_token" id="our_token">
                                    <table class="table table-bordered" id="results" style="width:100%">
                                        <thead>
                                            <tr>
                                                @php
                                                    $colspan = 0;
                                                        foreach ($subjects as $key => $subject){
                                                            $colspan +=1;
                                                        }

                                                    $exam_types_html = '';
                                                    $sbjcts_html = '';

                                                    /* starts here */
                                                    foreach ($subjects as $key => $subject) {
                                                    $subjct_span = 0;
                                                    foreach ($exam_type as $key => $exam) {
                                                    $exam = $examodel::find($exam);
                                                    $subjct_span +=1;
                                                        $exam_types_html.='
                                                        <th style="text-align:center" data-subject_id="'.$subject->subject_id.'" data-exam-id="'.$exam->id.'" class="text-center">'.$exam->code.'/'.$exam->total_marks.'</th>';
                                                    }
                                                    $exam_types_html.= '<th style="text-align:center" data-subject_id=""  class="text-center">AVG %</th> <th style="text-align:center" data-subject_id=""  class="text-center">GRADE</th> <th style="text-align:center" data-subject_id=""  class="text-center">POINTS</th>';
                                                    $subjct_span += 3;

                                                    $sbjcts_html.= '<th style="text-align:center" colspan="'.$subjct_span.'" data-subject_uuid="'.$subject->uuid.'">'.$subject->sbjct_code.' </th>';
                                                    $code  =   strtolower( str_replace(' ','_',$subject->sbjct_name));
                                                    $sbjct_columns[] = [
                                                    'data'=> $subject->sbjct_code,
                                                    'name'=> $code,
                                                    'subject_id'=> $subject->subject_id,
                                                    ];
                                                    // $subjects_count++;
                                                    $colspan +=1;
                                                    }

                                                    /* end here */

                                                @endphp




                                                <th rowspan="3"> <label style="display: flex"> <input type="checkbox"  class="checkbox checkall">  &nbsp;<span>C/U</span>  </label> </th>
                                                <th rowspan="3" class="frozen">SN</th>
                                                <th rowspan="3" class="frozen" style="text-align: center; width:20rem">ADMSN N0.</th>
                                                <th style="min-width:25rem" class="text-center frozen" rowspan="3">FULL NAME</th>
                                                <th style="text-align:center" class="text-center" colspan="{{ $colspan * 2}}">
                                                SUBJECTS
                                                </th>
                                                <th style="text-align:center" rowspan="3"> AVG </th>
                                                <th style="text-align:center" rowspan="3"> DIV </th>
                                                <th style="text-align:center" rowspan="3"> POINTS </th>
                                                <th style="text-align:center" rowspan="3"> C/T Comments </th>
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
                                                {{ $exam_types_html }}
                                            </tr>

                                            <tr>

{{--
                                                @foreach ($sbjct_columns as $key => $column)

                                                    <th style="text-align:center" data-subject_id="{{ $column['subject_id'] }}" class="text-center">Marks</th>
                                                    <th style="text-align:center" data-subject_id="{{ $column['subject_id'] }}" class="text-center">Grade</th>

                                                    @endforeach --}}

                                                </tr>


                                        </thead>

                                        <tbody>

                                            @foreach ($students->get() as $key => $student)
                                                    <tr>
                                                <td> {{ $key }} </td>
                                                <td> {{ $student->uuid }} </td>
                                                <td> {{ $student->firstname.' '.$student->lastname  }}</td>
                                                </tr>
                                                @endforeach
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

                    {{-- @if (auth()->user()->hasRole('Head Master'))

                    <h3 id="pb_h3" style="text-align: center; color:indigo !important"> Approve & Publish Results  </h3>
                    <div class="row" style="padding-right: 2rem; padding-left: 2rem; min-height:20rem; padding-top:1rem">
                        <div class="container">
                            <div style="display: flex; justify-content:space-around">

                                    <label class="checkbox-label">
                                        <input type="checkbox" value="1" {{ $generated_exam_report->include_signature ? 'checked' : ''   }} name="signature" class="checkbox-input" id="include-signature">
                                        <span style="padding-left: 1rem" class="checkbox-label-text">Include Signature</span>
                                    </label>

                                    <button href="javascript:void(0)" title="Publish" type="button"  style="color:white" data-uuid="{{ $generated_exam_report->uuid }}"  class="escalate_report btn btn-primary btn-sm  {{ $generated_exam_report->is_published ? 'disabled' : '' }}"> <i class="fa-solid fa-person-arrow-up-from-line"></i> Publish Results </button>
                            </div>


                            <div class="col-md-4">


                            </div>
                        </div>


                    </div>

                    @endif --}}



                </div>
            </div>


        </div>


        </div>


    </div>



    </div>


    <script>




    </script>




















