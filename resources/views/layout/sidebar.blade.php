<div class="page-sidebar">
    <a class="logo-box" href="{{ route('dashboard') }}">
    <span>
        <img src="{{ asset('assets/logo/sbrt_logo.gif') }}" alt="" width="45px">
        SRSS
    </span>
    <i class="ion-aperture" id="fixed-sidebar-toggle-button"></i>
    <i class="ion-ios-close-empty" id="sidebar-toggle-button-close"></i>
    </a>
    <div class="page-sidebar-inner">
       <div class="page-sidebar-menu">
          <ul class="accordion-menu">
            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Academic') || auth()->user()->hasRole('Head Master'))
            <li class="open active">
                <a href="{{ route('dashboard') }}"><i class="fa fa-tachometer"></i>
                <span>Dashboard</span></a>
             </li>
             @endif
             <li class="menu-divider mg-y-20-force"></li>
             <li class="mg-20-force menu-elements">MODULES</li>

             @if (auth()->user()->moduleParent(1))
             <li>
                <a href="javascript:void(0)"><i class="fa-solid fa-users"></i>
                <span>Students Management</span><i class="accordion-icon fa fa-angle-left"></i></a>
                <ul class="sub-menu">
                   <li> <a href="{{ route('students.ongoing')  }}">Active Student</a> </li>
                   <li> <a href="{{ route('students.graduated') }}">Graduate</a></li>
                   <li> <a href="{{ route('students.allumni') }}">Alumni</a></li>
                   <li> <a href="{{ route('students.promotion')}}">Promotion</a> </li>
                   <li> <a href="{{ route('students.manage')  }}">Manage Promotion</a> </li>
                </ul>
             </li>
             @endif

             @if (auth()->user()->moduleParent(33))
             <li>
                <a href="javascript:void(0)"><i class="fa fa-th"></i>
                <span>Academic</span><i class="accordion-icon fa fa-angle-left"></i></a>
                <ul class="sub-menu">
                    @if (auth()->user()->roleHasPermissions(42,7) || auth()->user()->hasRole('Admin'))
                   <li><a href="{{ route('exams.schedule.index')  }}">Exam Schedule</a></li>
                   @endif
                   @if  (auth()->user()->roleHasPermissions(35,3) || auth()->user()->hasRole('Admin'))
                   <li><a href="{{ route('exams.waiting.marking.index')  }}">Marking</a></li>
                   @endif

                   @if  (auth()->user()->roleHasPermissions(35,3) || auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Academic') || auth()->user()->hasRole('Head Master') )
                   <li><a href="{{ route('results.reports.index')  }}">Results/Reports</a></li>
                   @endif
                </ul>
             </li>
             @endif


             @if (auth()->user()->moduleParent(8))
             <li>
                <a href="javascript:void(0)"><i class="fa fa-cogs"></i>
                <span>Configurations</span><i class="accordion-icon fa fa-angle-left"></i></a>
                <ul class="sub-menu">
                   <li><a href="{{ route('configurations.general.index') }}"> General </a></li>
                   <li><a href="{{ route('academic.index')  }}">Academic</a></li>
                   <li><a href="{{ route('religion.index')  }}">Religion</a></li>
                   <li><a href="{{ route('houses.index')  }}">Houses</a></li>
                   <li><a href="{{ route('clubs.index')  }}">Clubs</a></li>
                   <li><a href="{{ route('configurations.security.index')  }}">Security</a></li>
                </ul>
             </li>
             @endif

             @if (auth()->user()->moduleParent(41))
             <li>
                <a href="{{ route('user.management.index') }}"><i class="fa fa-user"></i>
                <span>User Management</span></a>
             </li>
             @endif




          </ul>
       </div>
       <!--================================-->
       <!-- Sidebar Information Summary -->
       <!--================================-->

    </div>

 </div>



















