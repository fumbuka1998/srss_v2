<style>
    .badge {
    padding: 0.2rem 0.5rem !important;
    }
</style>


        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link  {{ $activeTab == 'personalInfoTab' ? 'active' : '' }}"  href="{{ route('students.profile',$student->student_uuid)  }}"><i style="color: #069613" class="fa-solid fa-student"></i>&nbsp;<span>PERSONAL INFO</span> <span class="badge badge-pill badge-primary"></span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'contactPersonTab' ? 'active' : '' }}"  href="{{ route('students.profile.contact.people.index',$student->student_uuid )  }}"><i style="color: #069613" class="fa-solid fa-folder-tree"></i>&nbsp;<span>CONTACT PERSON</span> <span class="badge badge-pill badge-primary"></span> </a>
            </li>

          <li class="nav-item">
            <a class="nav-link  {{ $activeTab == 'allocatedSbjctsTab' ? 'active' : '' }}" href="{{ route('students.profile.subjects.allocated.index',$student->student_uuid )  }}"> <i style="color: #069613" class="fa-regular font-icon fa-rectangle-list"></i>  &nbsp;<span>SUBJECTS ALLOCATED</span> <span class="badge badge-pill badge-info subjects_count"> {{ $assignedSubjects->count() }}  </span></a>
        </li>


            {{-- <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'attachmentsInfoTab' ? 'active' : '' }}"  href="{{ route('students.profile.attachments.index',$student->uuid ) }}"> <i style="color: #069613" class="fa-solid fa-file-contract"></i> &nbsp;<span>ATTACHMENTS</span> <span class="badge badge-pill  badge-dim badge-danger">  </span></a>
            </li> --}}

            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'studentResultsTab' ? 'active' : '' }}"  href="{{ route('students.results.preview.index',$student->student_uuid ) }}"> <i style="color: #069613" class="fa-solid fa-file-contract"></i> &nbsp;<span>RESULTS</span> <span class="badge badge-pill  badge-dim badge-danger">  </span></a>
            </li>
        </ul>



