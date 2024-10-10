<style>
    .badge {
    padding: 0.2rem 0.5rem !important;
    }
</style>


        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link  {{ $activeTab == 'downloadTemplateTab' ? 'active' : '' }}"  href="{{ route('students.registration.multiple')  }}"> <i style="color: #069613" class="fa fa-file-excel"></i>&nbsp;<span>DOWNLOAD EXCEL TEMPLATE</span> <span class="badge badge-pill badge-primary"></span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'UploadTab' ? 'active' : '' }}"  href="{{ route('students.registration.multiple.preview')  }}"><i style="color: #069613" class="fa-solid fa-folder-tree"></i>&nbsp;<span> UPLOAD DATA EXCEL </span> <span class="badge badge-pill badge-primary"></span> </a>
            </li>
        </ul>



