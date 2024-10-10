<div style="text-align: center;">
    <header class="clearfix">
        <div id="logo">
            {{-- <img src="{{ asset('assets/logo/default_logo.png') }}" alt="" style="width:100px;">
            <img style="object-fit: cover; width: 200px" name="school_logo" id="school_logo" src=" {{ $school_logo }} "
                alt="School Logo"> --}}

                <img src="{{ public_path('assets/logo/sbrt_logo.gif') }}" alt="Logo" style="width:100px;">

            <address class="address" style="line-height: 16px">
                <h2 style="color: #040404">Shaaban Robert Secondary School</h2>
                <strong style="color: #000000">P.0 BOX 736, DAR ES SALAAM TANZANIA</strong> <br>
                <strong style="color: #000000">Tel: +(255) 22 2114903 email:info@shaabanrobert.sc.tz</strong>
                <br /><strong>
            </address>
        </div>
    </header>
    <div>
        <div>
            <p style="font-size: 16px; text-align:center"> {{ $exam_name_combine }} RESULTS </p>

            <p style="font-size: 12px; margin-top:-18px; text-align:center"> SEMESTER - {{ $semester }}</p>
        </div>
    </div>

</div>
<div>

    <div class="aaa">
        <p>CLASS: {{ $class_name . ' ' . $stream_name }} </p>
    </div>

    <p>YEAR: {{ $year }}</p>

</div>
<br>




<div class="card mt-8" style="overflow-x: auto;">
    <div class="card-body">
        <div class="table-container">
            <table id="results" style="width:100%; font-size: 8px; word-wrap: break-word; border-collapse: collapse;text-align:center ">
                <thead style="background-color: green; color:white;">
                    <tr>
                        <th class="frozen" rowspan="3"
                            style="position: relative; left: 0; z-index: 2; width: 30px; border: 1px solid #000;">SN
                        </th>
                        <th class="frozen" rowspan="3"
                            style="position: relative; left: 0; z-index: 2; width: 30px; border: 1px solid #000;">
                            ADMISSION NUMBER</th>
                        <th class="text-center frozen" rowspan="3"
                            style="position: sticky; left: 0; z-index: 2; min-width: 70px; border: 1px solid #000;">FULL
                            NAME</th>
                        <th class="text-center" colspan="{{ $colspan }}" style="border: 1px solid #000;">SUBJECTS
                        </th>
                        <th class="text-center" rowspan="3"
                            style="position: relative; right: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                            AVG</th>
                        <th class="text-center" rowspan="3"
                            style="position: relative; right: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                            GRD</th>
                        <th class="text-center" rowspan="3"
                            style="position: relative; right: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                            DIV</th>
                        <th class="text-center" rowspan="3"
                            style="position: relative; right: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                            POINTS</th>
                        {{-- <th class="text-center" rowspan="3"
                            style="position: relative; right: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                            C/T Comments</th>
                        @if (auth()->user()->hasRole('Head Master') || $trigger_hm > 0)
                            <th class="text-center" rowspan="3"
                                style="position: relative; right: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                                H/M comments</th>
                        @endif --}}
                    </tr>
                    <tr>
                        @foreach ($sbjct_columns as $column)
                            <th class="text-center"
                                style="position: relative; left: 0; z-index: 2; min-width: 70px; border: 1px solid #000;"
                                colspan="{{ $column['subjct_span'] }}" data-subject_uuid="{{ $column['subjct_uuid'] }}">
                                {{ strtoupper($column['name']) }}
                            </th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($sbjct_columns as $column)
                            @foreach ($exam_type_columns as $exam_column)
                                @if ($column['subject_id'] == $exam_column['subject_id'])
                                    <th class="text-center"
                                        style="position: relative; left: 0; z-index: 2; min-width: 20px; border: 1px solid #000;">
                                        {{ $exam_column['exam']->code }} / {{ $exam_column['exam']->total_marks }}</th>
                                @endif
                            @endforeach
                            <th class="text-center"
                                style="position: relative; left: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                                AVG %</th>
                            {{-- <th class="text-center"
                                style="position: relative; left: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                                GRADE</th>
                            <th class="text-center"
                                style="position: relative; left: 0; z-index: 2; min-width: 10px; border: 1px solid #000;">
                                POINTS</th> --}}
                        @endforeach
                    </tr>
                </thead>


                <tbody>
                    @foreach ($matokeo as $key => $tokeo)
                        @php
                            $metadata = json_decode($tokeo->metadata);
                            $results = $metadata->results;

                            $avg = 0;
                            $checkpoints = 0;

                        @endphp

                        <tr style="border: 1px solid #000;">
                            <td style="border: 1px solid #000;">{{ ++$key }}</td>
                            <td style="border: 1px solid #000;">{{ $metadata->admission_no }}</td>
                            <td style="border: 1px solid #000; text-align:left" class="frozen frozen-td">{{ $metadata->full_name }}
                            </td>

                            @for ($i = 0; $i < count($sbjct_columns); $i++)
                                @foreach ($exam_type as $key => $column)
                                    @php
                                        $subject_id = $sbjct_columns[$i]['subject_id'];

                                        $remarks = '-';
                                        $avg = '-';
                                        $grade = '-';
                                        $score = '-';
                                        $points = '-';

                                        if (isset($results->$subject_id)) {
                                            $check = $results->$subject_id;

                                            if (isset($check->$column)) {
                                                $score = $check->$column;
                                            }
                                            $avg = $check->AVG;
                                            $grade = $check->GRADE;
                                            $points = $check->POINT;
                                        }
                                    @endphp

                                    <td style="border: 1px solid #000;">{{ $score }}</td>
                                @endforeach

                                <td style="border: 1px solid #000;">{{ $avg }}</td>
                                {{-- <td style="border: 1px solid #000;">{{ $grade }}</td>
                                <td style="border: 1px solid #000;">{{ $points }}</td> --}}
                            @endfor

                            <td style="border: 1px solid #000;">{{ isset($metadata->AVG) ? $metadata->AVG : 'N/A' }}
                            </td>
                            <td style="border: 1px solid #000;">
                                {{ isset($metadata->GRADE) ? $metadata->GRADE : 'N/A' }}</td>
                            <td style="border: 1px solid #000;">{{ isset($metadata->CODE) ? $metadata->CODE : 'N/A' }}
                            </td>
                            <td style="border: 1px solid #000;">
                                {{ isset($metadata->POINTS) ? $metadata->POINTS : 'N/A' }}</td>

                            {{-- @if (auth()->user()->id == $tokeo->user_id && $generated_exam_report->escalation_level_id == 1)
                                <td style="width: 10rem; border: 1px solid #000;">
                                    <select data-uuid="{{ $tokeo->uuid }}" data-student_id="{{ $tokeo->student_id }}"
                                        name="ct_comment[{{ $tokeo->student_id }}]"
                                        class="form-control select2s ct_comment form-control-sm">
                                        <option value="">Select A Comment.....</option>
                                        @foreach ($predefined_comments as $comment)
                                            <option value="{{ $comment->id }}"
                                                {{ $tokeo->ct_predefined_comment_ids == $comment->id ? 'selected' : '' }}>
                                                {{ $comment->comment }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            @else
                                <td style="width: 10rem; border: 1px solid #000; text-align:left">{{ $tokeo->ct_comments }}</td>
                            @endif --}}

                            {{-- @if (auth()->user()->hasRole('Head Master'))
                                <td style="width: 10rem; border: 1px solid #000;">
                                    <select data-uuid="{{ $tokeo->uuid }}" data-student_id="{{ $tokeo->student_id }}"
                                        name="ct_comment[{{ $tokeo->student_id }}]"
                                        class="form-control select2s ct_comment form-control-sm">
                                        <option value="">Select A Comment.....</option>
                                        @foreach ($predefined_comments as $comment)
                                            <option value="{{ $comment->id }}"
                                                {{ $tokeo->hm_predefined_comment_ids == $comment->id ? 'selected' : '' }}>
                                                {{ $comment->comment }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            @elseif ($tokeo->hm_comments)
                                <td style="min-width: 10em;border: 1px solid #000;">{{ $tokeo->hm_comments }}</td>
                            @endif --}}

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
