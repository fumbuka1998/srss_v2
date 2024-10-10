<style>
    .badge {
    padding: 0.2rem 0.5rem !important;
    }
</style>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'previewTab' ? 'active' : '' }}"  href="{{ route('results.reports.generated.reports.view.indrive',$uuid)  }}"><i style="color: #069613" class="fa-solid fa-folder-tree"></i>&nbsp;<span>Generated Report Preview</span> <span class="badge badge-pill badge-primary">     </span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'caTab' ? 'active' : '' }}" href="{{ route('character.assessments.index',$uuid)  }}"> <i style="color: #069613" class="fa-regular font-icon fa-rectangle-list"></i>  &nbsp;<span>Character Assessments</span> <span class="badge badge-pill badge-info"> </span></a>
            </li>
            <li class="nav-item">
                @if(auth()->user()->hasRole('Head Master'))
                <a class="nav-link {{ $activeTab == 'escalationIndexTab' ? 'active' : '' }}"  href="{{ route('results.reports.publish.index',$uuid)  }}"> <i style="color: #069613" class="fa-solid fa-list-check"></i> &nbsp;<span> Approve & Publish  </span> <span class="badge badge-pill badge-success"> </span> </a>
                @else  
                <a class="nav-link {{ $activeTab == 'escalationIndexTab' ? 'active' : '' }}"  href="{{ route('results.reports.escalation.index',$uuid)  }}"> <i style="color: #069613" class="fa-solid fa-list-check"></i> &nbsp;<span>   Escalation  </span> <span class="badge badge-pill badge-success"> </span> </a>
                 @endif
            </li>
        </ul>
