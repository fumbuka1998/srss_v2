<style>
    .badge {
    padding: 0.2rem 0.5rem !important;
    }
</style>


        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link  {{ $activeTab == 'personalInfoTab' ? 'active' : '' }}"  href="{{ route('users.management.profile',$user->uuid)  }}"><i style="color: #069613" class="fa-solid fa-user"></i>&nbsp;<span>PERSONAL INFO</span> <span class="badge badge-pill badge-primary"></span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'contactPersonTab' ? 'active' : '' }}"  href="{{ route('users.management.profile.contact.people.index',$user->uuid )  }}"><i style="color: #069613" class="fa-solid fa-folder-tree"></i>&nbsp;<span>CONTACT PERSON</span> <span class="badge badge-pill badge-primary"></span> </a>
            </li>
          @if ($show_subjects_allocation)
          <li class="nav-item">
            <a class="nav-link  {{ $activeTab == 'allocatedSbjctsTab' ? 'active' : '' }}" href="{{ route('users.management.profile.subjects.allocated.index',$user->uuid )  }}"> <i style="color: #069613" class="fa-regular font-icon fa-rectangle-list"></i>  &nbsp;<span>SUBJECTS ALLOCATED</span> <span class="badge badge-pill badge-info"> 10  </span></a>
        </li>
          @endif

            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'attachmentsInfoTab' ? 'active' : '' }}"  href="{{ route('users.management.profile.attachments.index',$user->uuid ) }}"> <i style="color: #069613" class="fa-solid fa-file-contract"></i> &nbsp;<span>ATTACHMENTS</span> <span class="badge badge-pill  badge-dim badge-danger">  </span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'loginHistoryTab' ? 'active' : '' }}"  href="{{ route('users.management.profile.login.history',$user->uuid ) }}"> <i style="color: #069613" class="fa-solid fa-file-contract"></i> &nbsp;<span>LOGIN HISTORY</span> <span class="badge badge-pill  badge-dim badge-danger">  </span></a>
            </li>
        </ul>



