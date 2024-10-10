
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'generateReportTab' ? 'active' : '' }}"  href="{{ route('results.reports.class.report.exam.generate')   }}"><i style="color: #069613" class="fa-solid fa-folder-tree"></i>&nbsp;<span>Create Reports</span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'generatedReportsTab' ? 'active' : '' }}" href="{{ route('results.reports.generated.reports.index')  }}"> <i style="color: #069613" class="fa-regular font-icon fa-rectangle-list"></i>  &nbsp;<span>{{ auth()->user()->hasRole('Head Master') ? 'Waiting For Publish' : 'Generated Reports'  }}</span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'completeTab' ? 'active' : '' }}"  href="{{ route('results.reports.loader.index')  }}"> <i style="color: #069613" class="fa-solid fa-list-check"></i> &nbsp;<span>Published Results</span> </a>
            </li>
        </ul>

