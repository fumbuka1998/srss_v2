

<style>
    .badge {
    padding: 0.2rem 0.5rem !important;
    }
</style>

        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'roles' ? 'active' : '' }}" href="{{ route('configurations.security.roles')  }}"> <i style="color: #069613" class="fa-solid font-icon fa-lock"></i>  &nbsp;<span>Roles</span> </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'permissions' ? 'active' : '' }}" href="{{ route('configurations.security.roles.permissions')  }}"> <i style="color: #069613" class="fa-solid font-icon fa-door-open"></i>   &nbsp;<span>Permissions</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeTab == 'modules' ? 'active' : '' }}" href="{{ route('configurations.security.modules')  }}"> <i style="color: #069613" class="fa-regular font-icon fa-rectangle-list"></i>  &nbsp;<span>Modules</span> </a>
            </li>

        </ul>
