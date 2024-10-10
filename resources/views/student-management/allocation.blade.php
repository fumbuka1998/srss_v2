
<style>

#table th{
    color: white !important;
    background:#069613;
}

.checkuncheck{
    cursor: pointer;
}

/* .animated-checkbox{
    cursor: pointer;
    width: 1.3em;
    height: 1.3em;
} */

</style>
<div class="table-container-scroll">

    <table id="table"  class="table table-striped table-bordered table-sm"  style="width: 100%; table-layout: inherit;">
        <thead>
            <tr>
              <th>Select All</th>
              <th>Class</th>
              <th>Stream</th>
              <th style="min-width: 15rem">Full Name</th>
              <th>Subjects</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($students as  $student)

            <tr>
                <td>
                    <input type="checkbox" class="select-all-checkbox" data-student-uuid="{{ $student->uuid }}">
                </td>
                <td></td>
                <td></td>
                <td>{{ strtoupper($student->firstname . ' '. $student->middlename . ' '.$student->lastname)  }}</td>

                <td>

                    <div style="display: flex; align-items:center">
                        @foreach ($all_subjects as $index => $subject )

                        @php
                            $checked = false;
                            if ($student->subjectsAssignments) {

                                foreach ( $student->subjectsAssignments as $assigned )
                                if ($assigned->subject->id == $subject->id) {
                                    $checked = true;
                                    break;
                                }

                            }

                            @endphp


                        <input  type="checkbox" style="margin-left: 1.5rem;"
                                data-subject-id="{{ $subject->id  }}"
                                data-student-uuid="{{ $student->uuid  }}"
                                value="{{ $subject->id  }}"
                                name="subject"
                                data-student-id="{{ $student->id }}"
                                data-class-id="{{ $student->class_id }}"
                                data-stream-id = "{{ $student->stream_id }}"
                                class="animated-checkbox checkuncheck"
                                {{ $checked ? 'checked' : ''  }}>
                        <span style="margin-left: 0.5rem;" class="text-color font">{{ $subject->name }} </span>


                        @endforeach

                    </div>





                </td>
            </tr>

            @endforeach
        </tbody>



    </table>

</div>







{{-- all_subjects --}}
