

    <div class="col-md-12">
        <form action="#" id="upload">
        <input type="hidden" name="class_id" value="{{ $class_id }}">
        <input type="hidden" name="stream_id" value="{{$stream_id}}">
        <input type="hidden" name="exam_type" value="{{ $exam_type }}">
        <input type="hidden" name="subject_id" value="{{ $subject_type }}">
        <input type="hidden" name="academic_year" value="{{$acdmcyear}}">
        <input type="hidden" name="semester" value="{{$semester}}">
        <input type="hidden" name="uuid" value="{{ $uuid }}">
        <input type="hidden" name="sp" value="{{ $sp }}">
        <input type="hidden" name="gg" value="{{$gg}}">

        <table class="table table-bordered"> 
            <thead>
                <tr>
                    <th class="d-none d-sm-table-cell" style="color: white; background: #069613; width:10rem">Admission Number</th>
                    <th style="color: white; background: #069613;">Name</th>
                    <th style="color: white; background: #069613;" >Score/{{$examInfo->total_marks }}</th>
                </tr>

            </thead>
            {{-- <tbody>
                @foreach ($students->get() as $key => $student)
                <tr>
                    <td class="d-none d-sm-table-cell td-style" >{{ $student->id }}</td>
                    <td class="td-style">{{ $student->full_name }}</td>
                    <td style="width:1rem" class="td-style" data-student-id="{{ $student->id }}">
                        <div>
                        <input class="input form-control marks mark-field" type="text" name="marks[]" min="0" max="{{ $examInfo->total_marks }}">
                        <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                        <input type="hidden" name="full_name[]" value="{{ $student->full_name }}">
                        </div>
                    </td>
                    </tr>
                    @endforeach
            </tbody> --}}


            <tbody>
                @foreach ($students as $key => $student)
                <tr>
                    <td class="d-none d-sm-table-cell td-style">{{ $student->id }}</td>
                    <td class="td-style">{{ $student->full_name }}</td>
                    <td style="width:1rem" class="td-style" data-student-id="{{ $student->id }}">
                        <div>
                            <input class="input form-control marks mark-field" type="text" name="marks[]" min="0" max="{{ $examInfo->total_marks }}">
                            <input type="hidden" name="student_id[]" value="{{ $student->id }}">
                            <input type="hidden" name="full_name[]" value="{{ $student->full_name }}">
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            
        </table>

    </form>

    </div>

    <div class="col-md-12 mb-8">
        <span style="float: right; margin-right: 1.5rem; margin-bottom: 2rem;">
            @if ($students_count)
            <button id="draftsbmt" class="btn text-white draft-sbmt btn-sm btn-warning"><i class="fa-solid fa-floppy-disk"></i>  Draft</button> &nbsp; 
             <button disabled id="sbmt" class="btn btn-success btn-sm"> <i class="fa-solid sbmt-result fa-cloud-arrow-up"></i>Submit</button>
            @endif
        </span>

    </div>


