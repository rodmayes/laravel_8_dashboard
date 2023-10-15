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

        <p class="uppercase text-xs text-gray-800 mb-4 tracking-wider">home</p>
<!--  {{ request()->is("admin") ? "menu-is-opening menu-open" : "" }} -->
        <!-- link -->
        <a href="{{ route("admin.home") }}" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500">
            <i class="fad fa-chart-pie text-xs mr-2"></i>
            {{ trans('global.dashboard') }}
        </a>
        <!-- end link -->

        <!-- PLAYTOMIC -->
        <p class="uppercase text-xs text-gray-800 mb-4 mt-4 tracking-wider">{{ trans('playtomic.title') }}</p>
        @can('playtomic_club_access')
        <!-- link -->
        <a href="{{ route("playtomic.clubs.index") }}" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
           @if(Route::is('playtomic.clubs.*')) text-warning-600 @endif">
            <i class="fad fa-medal text-xs mr-2"></i>
            {{ trans('playtomic.clubs.title') }}
        </a>
        <!-- end link -->
        @endcan
        @can('playtomic_resources_access')
        <!-- link -->
        <a href="{{ route("playtomic.resources.index") }}" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
           @if(Route::is('playtomic.resources.*')) text-warning-600 @endif">
            <i class="fad fa-table-tennis text-xs mr-2"></i>
            {{ trans('playtomic.resources.title') }}
        </a>
        <!-- end link -->
        @endcan
        @can('playtomic_booking_access')
        <!-- link -->
        <a href="{{ route("playtomic.bookings.index") }}" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
        @if(Route::is('playtomic.bookings.*')) text-warning-600 @endif">
            <i class="fad fa-baseball-ball text-xs mr-2"></i>
            {{ trans('playtomic.bookings.title') }}
        </a>
        <!-- end link -->
        @endcan


        @can('user_management_access')
        <!-- USERS -->
        <p class="uppercase text-xs text-gray-800 mb-4 mt-4 tracking-wider">{{ trans('cruds.userManagement.title') }}</p>
        @can('user_access')
        <!-- link -->
        <a href="{{ route("admin.users.index") }}" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
        @if(Route::is('admin.users.*')) text-warning-600 @endif">
            <i class="fad fa-medal text-xs mr-2"></i>
            {{ trans('cruds.user.title') }}
        </a>
        <!-- end link -->
        @endcan
        @can('role_access')
        <!-- link -->
        <a href="{{ route("admin.roles.index") }}" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
        @if(Route::is('admin.roles.*')) text-warning-600 @endif">
            <i class="fad fa-table-tennis text-xs mr-2"></i>
            {{ trans('cruds.role.title') }}
        </a>
        <!-- end link -->
        @endcan
        @can('permission_access')
        <!-- link -->
        <a href="{{ route("admin.permissions.index") }}" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500
        @if(Route::is('admin.permissions.*')) text-warning-600 @endif">
            <i class="fad fa-baseball-ball text-xs mr-2"></i>
            {{ trans('cruds.permission.title') }}
        </a>
        <!-- end link -->
        @endcan
        @endcan
        @can('administration_access')
        <p class="uppercase text-xs text-gray-800 mb-4 mt-4 tracking-wider">Administration</p>
        @can('access_logs')
        <!-- link -->
        <a href="/logs" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500 @if(Route::is('logs')) text-warning-600 @endif">
            <i class="fad fa-cogs text-xs mr-2"></i>
            Logs old
        </a>
        <!-- end link -->
        <!-- link -->
        <a href="/log-viewer" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500 @if(Route::is('log-viewer/*')) text-warning-600 @endif">
            <i class="fad fa-cogs text-xs mr-2"></i>
            Logs
        </a>
        <!-- end link -->
        @endcan
        @can('access_telescope')
        <!-- link -->
        <a href="/telescope" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500" target="_blank">
            <i class="fad fa-cogs text-xs mr-2"></i>
            Telescope
        </a>
        <!-- end link -->
        @endcan
        @can('access_scheduled_tasks')
        <!-- link -->
        <a href="/schedule" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500">
            <i class="fad fa-cogs text-xs mr-2"></i>
            Scheduled tasks
        </a>
        <!-- end link -->
        @endcanany
        @can('access_console')
        <!-- link -->
        <a href="/console" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500 @if(Route::is('console')) text-warning-600 @endif">
            <i class="fad fa-cogs text-xs mr-2"></i>
            Console
        </a>
        <!-- end link -->
        @endcan
        <!-- link -->
        @endcan
        <!--
        <a onclick="event.preventDefault(); document.getElementById('logoutform').submit();" class="mb-3 capitalize font-medium text-sm hover:text-black transition ease-in-out duration-500">
            <i class="fad fa-sign-out-alt text-xs mr-2"></i>
            {{ trans('global.logout') }}
        </a>
        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
        -->
        <!-- end link -->
    </div>
    <!-- end sidebar content -->
</div>
<!-- end sidbar -->
