
    <div class="sidebar os-theme-dark">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
               <img src="{{asset('images/heroina.webp')}}" class="img-circle elevation-2" alt="User Image" style="height:35px">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{Auth::user()->name}}</a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact text-sm" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item {{ request()->is("admin") ? "menu-is-opening menu-open" : "" }}">
                    <a href="{{ route("admin.home") }}" class="nav-link {{ request()->is("admin") ? "active" : "" }}">
                        <i class="nav-icon fas fa-tv"></i>
                        <p>{{ trans('global.dashboard') }}</p>
                    </a>
                </li>
                <!-- PLAYTOMIC -->
                <li class="nav-item {{ request()->is("playtomic*")||request()->is("playtomic/**") ? "menu-is-opening menu-open" : "" }}">
                    <a href="#" class="nav-link {{ request()->is("playtomic*")||request()->is("playtomic/**") ? "active" : "" }}">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            {{ trans('playtomic.title') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('playtomic_club_access')
                            <li class="class-link {{ request()->is("playtomic/clubs*") ? "menu-is-opening menu-open" : "" }}">
                                <a href="{{ route("playtomic.clubs.index") }}" class="nav-link {{ request()->is("playtomic/clubs*") ? "active" : "" }}">
                                    <i class="fa-fw c-sidebar-nav-icon fas fa-briefcase"></i>
                                    <p>{{ trans('playtomic.clubs.title') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('playtomic_resources_access')
                            <li class="class-link {{ request()->is("playtomic/resources*") ? "menu-is-opening menu-open" : "" }}">
                                <a href="{{ route("playtomic.resources.index") }}" class="nav-link {{ request()->is("playtomic/resources*") ? "active" : "" }}">
                                    <i class="fa-fw c-sidebar-nav-icon fas fa-briefcase"></i>
                                    <p>{{ trans('playtomic.resources.title') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('playtomic_booking_access')
                        <li class="class-link {{ request()->is("playtomic/bookings*") ? "menu-is-opening menu-open" : "" }}">
                            <a href="{{ route("playtomic.bookings.index") }}" class="nav-link {{ request()->is("playtomic/bookings*") ? "active" : "" }}">
                                <i class="fa-fw c-sidebar-nav-icon fas fa-briefcase"></i>
                                <p>{{ trans('playtomic.bookings.title') }}</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                <!-- END PLAYTOMIC -->

                <!-- USERS -->
                @can('user_management_access')
                <li class="nav-item {{ request()->is("admin/permissions*")||request()->is("admin/roles*")||request()->is("admin/users*") ? "menu-is-opening menu-open" : "" }}">
                    <a href="#" class="nav-link {{ request()->is("admin/permissions*") ? "sidebar-nav-active" : "menu-open" }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            {{ trans('cruds.userManagement.title') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('permission_access')
                            <li class="class-link {{ request()->is("admin/permissions*") ? "menu-is-opening menu-open" : "" }}">
                                <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is("admin/permissions*") ? "active" : "" }}">
                                    <i class="fa-fw c-sidebar-nav-icon fas fa-unlock-alt"></i>
                                    <p>{{ trans('cruds.permission.title') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="class-link {{ request()->is("admin/roles*") ? "menu-is-opening menu-open" : "" }}">
                                <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is("admin/roles*") ? "active" : "" }}">
                                    <i class="fa-fw c-sidebar-nav-icon fas fa-briefcase"></i>
                                    <p>{{ trans('cruds.role.title') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('user_access')
                                <li class="class-link {{ request()->is("admin/users*") ? "menu-is-opening menu-open" : "" }}">
                                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users*") ? "active" : "" }}">
                                        <i class="fa-fw c-sidebar-nav-icon fas fa-user"></i>
                                        <p>{{ trans('cruds.user.title') }}</p>
                                    </a>
                                </li>
                        @endcan
                    </ul>
                </li>
                @endcan
                <!-- END USERS -->
                <li class="class-link {{ request()->is("logs") ? "menu-is-opening menu-open" : "" }}">
                    <a href="/logs" class="nav-link {{ request()->is("logs") ? "active" : "" }}">
                        <i class="fas fa-cogs"></i>
                        Logs
                    </a>
                </li>

                <li class="class-link {{ request()->is("telescope") ? "menu-is-opening menu-open" : "" }}">
                    <a href="/telescope" class="nav-link {{ request()->is("telescope") ? "active" : "" }}" target="_blank">
                        <i class="fas fa-cogs"></i>
                        Telescope
                    </a>
                </li>

                <li class="class-link {{ request()->is("schedule") ? "menu-is-opening menu-open" : "" }}">
                    <a href="/schedule" class="nav-link {{ request()->is("schedule") ? "active" : "" }}" target="_blank">
                        <i class="fas fa-cogs"></i>
                        Scheduled tasks
                    </a>
                </li>

                <li class="class-link {{ request()->is("console") ? "menu-is-opening menu-open" : "" }}">
                    <a href="/console" class="nav-link {{ request()->is("console") ? "active" : "" }}" target="_blank">
                        <i class="fas fa-cogs"></i>
                        Console
                    </a>
                </li>

                <li class="class-link">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();" class="nav-link">
                        <i class="fa-fw fas fa-sign-out-alt"></i>
                        {{ trans('global.logout') }}
                    </a>
                </li>

            </ul>
        </nav>
    </div>
