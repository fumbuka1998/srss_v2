<style>
    .badge {
    padding: 0.2rem 0.5rem !important;
    }
</style>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'upcomingExamsTab' ? 'active' : '' }}"  href="{{ route('exams.upcoming.marking.index')  }}"><i style="color: #069613" class="fa-solid fa-folder-tree"></i>&nbsp;<span>Upcoming Exams</span> <span class="badge badge-pill badge-primary">{{ $upcomingForMarkingCount ? $upcomingForMarkingCount : 0 }}     </span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'waitingForMarkingExamsTab' ? 'active' : '' }}" href="{{ route('exams.waiting.marking.index')  }}"> <i style="color: #069613" class="fa-regular font-icon fa-rectangle-list"></i>  &nbsp;<span>Waiting For Marking</span> <span class="badge badge-pill badge-info"> {{ $waitingForMarkingCount ? $waitingForMarkingCount : 0 }}   </span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'completeTab' ? 'active' : '' }}"  href="{{ route('results.sytem.complete.entry.index')  }}"> <i style="color: #069613" class="fa-solid fa-list-check"></i> &nbsp;<span>Completed Marking</span> <span class="badge badge-pill badge-success">  {{ $completedCount ? $completedCount : 0 }}  </span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'incompleteTab' ? 'active' : '' }}"  href="{{ route('results.sytem.incomplete.entry.index') }}"> <i style="color: #069613" class="fa-solid fa-file-word"></i> &nbsp;<span>Incomplete Marking</span> <span class="badge badge-pill badge-warning"> {{ $inCompletedCount ? $inCompletedCount : 0 }}  </span></a>
            </li>

            {{-- {{ route('results.sytem.drafts.entry.index') }} --}}
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'draftsTab' ? 'active' : '' }}"  href="#"> <i style="color: #069613" class="fa-solid fa-file-contract"></i> &nbsp;<span>Drafts</span> <span class="badge badge-pill  badge-dim badge-danger"> {{ $draftsCount ? $draftsCount : 0 }} </span></a>
            </li>
        </ul>


