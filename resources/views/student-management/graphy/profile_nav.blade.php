<div class="card">


    <ul class="nav nav-pills mb-3" role="tablist">
        <li class="nav-item   {{ $activeTab == 'profile' ? 'active' : '' }}">
          <a class="nav-link"  href="{{ route('students.profile',$student->student_uuid)  }}" aria-controls="pills-home" aria-selected="true">Profile</a>
        </li>
        <li class="nav-item {{ $activeTab == 'assigned_subjects' ? 'active' : '' }}">
          <a class="nav-link"   href="{{ route('students.subjects.assignment.index',$student->student_uuid)  }}" role="tab" aria-controls="pills-profile" aria-selected="false">Assigned Subjects</a>
        </li>
        <li class="nav-item {{ $activeTab == 'files' ? 'active' : '' }}">
          <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Files & Attachments</a>
        </li>

        <li class="nav-item {{ $activeTab == 'next_of_kin' ? 'active' : '' }}">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Parents & Guardians</a>
          </li>

          <li class="nav-item {{ $activeTab == 'results' ? 'active' : '' }}">
            <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Results</a>
          </li>
      </ul>

      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">...</div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div>
      </div>

</div>
