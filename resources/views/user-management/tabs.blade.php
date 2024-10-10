


<ul class="nav nav-tabs custom-menu-wrap custon-tab-menu-style1">
    <li class="{{ $activeTab == 'users' ? 'active' : '' }}"><a  href="{{ route('configurations.security.roles')  }}"><span class=" fa big-icon fa-user-management tab-custon-ic"> &nbsp;</span>List</a>
    </li>
    <li class="{{ $activeTab == 'permissions' ? 'active' : '' }}"><a  href="{{ route('configurations.security.roles.permissions')  }}"><span class="adminpro-icon adminpro-analytics-arrow tab-custon-ic"></span>Password</a>
    </li>
</ul>
