<!-- start sidebar -->
<div id="sideBar" class="relative flex flex-col flex-wrap border-r border-gray-300 p-6 flex-none w-64 md:-ml-64 md:fixed md:top-0 md:z-30 md:h-screen md:shadow-xl animated faster text-white bg-teal-400">
    <!-- sidebar content -->
    <div class="flex flex-col">
        <!-- sidebar toggle -->
        <div class="text-right hidden md:block mb-4">
            <button id="sideBarHideBtn">
                <i class="fad fa-times-circle"></i>
            </button>
        </div>
        <!-- end sidebar toggle -->
        <aside  aria-label="Sidebar">
            <div class="overflow-y-auto rounded">
                <ul class="space-y-2">
                    <!-- DASHBOARD -->
                    <li>
                        <a href="{{ route("admin.home") }}" class="flex items-center uppercase font-medium text-sm hover:text-black transition ease-in-out duration-500 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg">
                            <i class="fad fa-dot-circle text-xs mr-2"></i>
                            {{ trans('global.dashboard') }}
                        </a>
                    </li>
                    <!-- END DASHBOARD -->
                    @can('hasRole', 'playtomic')
                        <!-- PLAYTOMIC -->
                        <li>
                            <button type="button" class="flex items-center w-full font-medium text-sm hover:text-black transition ease-in-out duration-500 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg focus:outline-none"
                                    aria-controls="dropdown-playtomic" data-collapse-toggle="dropdown-playtomic">
                                <i class="fad fa-dot-circle text-xs"></i>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap uppercase" sidebar-toggle-item>{{ trans('playtomic.title') }}</span>
                                <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                            </button>
                            <ul id="dropdown-playtomic" class="@if(Route::is('playtomic.*')) '' @else hidden @endif py-2 space-y-2">
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="{{ route("playtomic.timetables.index") }}" class="flex mb-3 pl-4 capitalize font-medium text-sm transition ease-in-out duration-500
                                    @if(Route::is('playtomic.timetables.*')) text-warning-600 @endif">
                                            <i class="fad fa-clock text-xs mr-2"></i>
                                            {{ trans('playtomic.timetable.title') }}
                                        </a>
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="{{ route("playtomic.clubs.index") }}" class="flex mb-3 pl-4 capitalize font-medium text-sm transition ease-in-out duration-500
                                    @if(Route::is('playtomic.clubs.*')) text-warning-600 @endif">
                                            <i class="fad fa-medal text-xs mr-2"></i>
                                            {{ trans('playtomic.clubs.title') }}
                                        </a>
                                    </li>
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg">
                                        <a href="{{ route("playtomic.resources.index") }}" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
                                    @if(Route::is('playtomic.resources.*')) text-warning-600 @endif">
                                            <i class="fad fa-table-tennis text-xs mr-2"></i>
                                            {{ trans('playtomic.resources.title') }}
                                        </a>
                                    </li>
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="{{ route("playtomic.bookings.index") }}" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
                                    @if(Route::is('playtomic.bookings.*')) text-warning-600 @endif">
                                            <i class="fad fa-baseball-ball text-xs mr-2"></i>
                                            {{ trans('playtomic.bookings.title') }}
                                        </a>
                                    </li>
                            </ul>
                        </li>
                    @endcan

                    <!-- USER MANAGEMENT -->
                    @can('user_management.access')
                        <li>
                            <button type="button" class="flex items-center w-full font-medium text-sm hover:text-black transition ease-in-out duration-500 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg focus:outline-none"
                                    aria-controls="dropdown-user_management" data-collapse-toggle="dropdown-user_management">
                                <i class="fad fa-dot-circle text-xs"></i>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap uppercase" sidebar-toggle-item>{{ trans('cruds.userManagement.title') }}</span>
                                <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                            </button>
                            <ul id="dropdown-user_management" class="@if(Route::is('user_management.*')) '' @else hidden @endif py-2 space-y-2">
                                @can('user_management.user_access')
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="{{ route("user_management.users.index") }}" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
                                        @if(Route::is('user_management.users.*')) text-warning-600 @endif">
                                            <i class="fad fa-users text-xs mr-2"></i>
                                            {{ trans('cruds.user.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('user_management.role_access')
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="{{ route("user_management.roles.index") }}" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
                                    @if(Route::is('user_management.roles.*')) text-warning-600 @endif">
                                            <i class="fad fa-user-tag text-xs mr-2"></i>
                                            {{ trans('cruds.role.title') }}
                                        </a>
                                    </li>
                                @endcan
                                @can('user_management.permission_access')
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="{{ route("user_management.permissions.index") }}" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
                                            @if(Route::is('user_management.permissions.*')) text-warning-600 @endif">
                                            <i class="fad fa-user-shield text-xs mr-2"></i>
                                            {{ trans('cruds.permission.title') }}
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    <!-- END USER MANAGEMENT -->

                    <!-- Lottery -->
                    @can('lottery.access')
                        <li>
                            <button type="button" class="flex items-center w-full font-medium text-sm hover:text-black transition ease-in-out duration-500 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg focus:outline-none"
                                    aria-controls="dropdown-logs" data-collapse-toggle="dropdown-logs">
                                <i class="fad fa-dot-circle text-xs"></i>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap uppercase" sidebar-toggle-item>Lottery</span>
                                <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                            </button>
                            <ul id="dropdown-logs" class="@if(Route::is('lottery.*')) '' @else hidden @endif py-2 space-y-2">
                                <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                    <a href="{{route('lottery.combinations')}}" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500 @if(Route::is('lottery.index')) text-warning-600 @endif">
                                        <i class="fad fa-cogs text-xs mr-2"></i>
                                        Combinations
                                    </a>
                                </li>
                                <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                    <a href="{{route('lottery.results')}}" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500 @if(Route::is('lottery.results')) text-warning-600 @endif">
                                        <i class="fad fa-cogs text-xs mr-2"></i>
                                        Results
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    <!-- END LOGS -->

                    <!-- LOGS -->
                    @can('logs.access')
                        <li>
                            <button type="button" class="flex items-center w-full font-medium text-sm hover:text-black transition ease-in-out duration-500 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg focus:outline-none"
                                    aria-controls="dropdown-logs" data-collapse-toggle="dropdown-logs">
                                <i class="fad fa-dot-circle text-xs"></i>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap uppercase" sidebar-toggle-item>Logs</span>
                                <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                            </button>
                            <ul id="dropdown-logs" class="@if(Route::is('logs.*')) '' @else hidden @endif py-2 space-y-2">
                                <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                    <a href="/logs" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500 @if(Route::is('logs')) text-warning-600 @endif">
                                        <i class="fad fa-cogs text-xs mr-2"></i>
                                        Old
                                    </a>
                                </li>
                                <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                    <a href="/log-viewer" class="flex mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500 @if(Route::is('log-viewer/*')) text-warning-600 @endif">
                                        <i class="fad fa-cogs text-xs mr-2"></i>
                                        New
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endcan
                    <!-- END LOGS -->

                    <!-- ADMINISTRATION -->
                    @can('logs.access')
                        <li>
                            <button type="button" class="flex items-center w-full font-medium text-sm hover:text-black transition ease-in-out duration-500 hover:bg-gray-100 dark:hover:bg-gray-700 p-2 rounded-lg"
                                    aria-controls="dropdown-administration" data-collapse-toggle="dropdown-administration">
                                <i class="fad fa-dot-circle text-xs"></i>
                                <span class="flex-1 ml-3 text-left whitespace-nowrap uppercase" sidebar-toggle-item>Administration</span>
                                <svg sidebar-toggle-item class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd">
                                    </path>
                                </svg>
                            </button>
                            <ul id="dropdown-administration" class="@if(Route::is('administration.*')) '' @else hidden @endif py-2 space-y-2">
                                @can('administration.telescope_access')
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="/telescope" class="mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500" target="_blank">
                                            <i class="fad fa-cogs text-xs mr-2"></i>
                                            Telescope
                                        </a>
                                    </li>
                                @endcan
                                @can('administration.scheduled_tasks_access')
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="/totem" class="mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500" target="_blank">
                                            <i class="fad fa-cogs text-xs mr-2"></i>
                                            Scheduled tasks
                                        </a>
                                    </li>
                                @endcan
                                @can('administration.console_access')
                                    <li class="hover:text-black hover:bg-teal-300 pt-2 pt-1 rounded-lg items-center">
                                        <a href="/console" class="mb-3 pl-4 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500 @if(Route::is('console')) text-warning-600 @endif">
                                            <i class="fad fa-cogs text-xs mr-2"></i>
                                            Console
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcan
                    <!-- END ADMINISTRATION -->

                </ul>
            </div>
        </aside>

        <div class="absolute bottom-0 left-0 p-4">
            <a onclick="event.preventDefault(); document.getElementById('logoutform').submit();" class="mb-3 top-0 left-0 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500">
                <i class="fad fa-sign-out-alt text-xs mr-2"></i>
                {{ trans('global.logout') }}
            </a>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>
