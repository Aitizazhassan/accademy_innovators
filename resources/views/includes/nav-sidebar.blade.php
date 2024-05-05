<!-- Sidebar -->
    <nav id="sidebar" aria-label="Main Navigation">
    <!-- Side Header -->
    <div class="bg-header-dark">
        <div class="content-header bg-white-5">
        <!-- Logo -->
        <a class="fw-semibold text-white tracking-wide" href="{{ url('/') }}">
            {{-- <span class="smini-visible">
            D<span class="opacity-75">x</span>
            </span>
            <span class="smini-hidden">
            Capasso<span class="opacity-75">Ent</span>
            </span> --}}
            <img class="logo-img img-fluid" src="{{ asset('images/logo/2.png') }}" alt="">
        </a>
        <!-- END Logo -->

        <!-- Options -->
        <div>
            <!-- Toggle Sidebar Style -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <!-- Class Toggle, functionality initialized in Helpers.dmToggleClass() -->
            {{-- <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle" data-target="#sidebar-style-toggler" data-class="fa-toggle-off fa-toggle-on" onclick="Dashmix.layout('sidebar_style_toggle');Dashmix.layout('header_style_toggle');">
            <i class="fa fa-toggle-off" id="sidebar-style-toggler"></i>
            </button> --}}
            <!-- END Toggle Sidebar Style -->

            <!-- Dark Mode -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle" data-target="#dark-mode-toggler" data-class="far fa" onclick="Dashmix.layout('dark_mode_toggle');">
            <i class="far fa-moon" id="dark-mode-toggler"></i>
            </button>
            <!-- END Dark Mode -->

            <!-- Close Sidebar, Visible only on mobile screens -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
            <i class="fa fa-times-circle"></i>
            </button>
            <!-- END Close Sidebar -->
        </div>
        <!-- END Options -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Sidebar Scrolling -->
    <div class="js-sidebar-scroll">
        <!-- Side Navigation -->
        <div class="content-side">
        <ul class="nav-main">
            <li class="nav-main-item">
            <a class="nav-main-link {{ in_array(Route::currentRouteName(), ['dashboard']) ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="nav-main-link-icon fa fa-location-arrow"></i>
                <span class="nav-main-link-name">Dashboard</span>
            </a>
            </li>

            <li class="nav-main-heading">Base</li>
            <li class="nav-main-item {{ in_array(Route::currentRouteName(), ['users.index', 'users.setting', 'roles.index', 'roles.create', 'roles.edit', 'roles.edit.permissions']) ? 'open' : '' }}">
                <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true"
                    aria-expanded="false" href="#">
                    <i class="nav-main-link-icon fa fa-wrench"></i>
                    <span class="nav-main-link-name">Settings</span>
                </a>
                <ul class="nav-main-submenu">

                    {{-- users --}}
                    @can('user.view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ in_array(Route::currentRouteName(), ['users.index']) ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <i class="nav-main-link-icon fa fa-users"></i><span class="nav-main-link-name">Users</span>
                        </a>
                    </li>
                    @endcan

                    {{-- roles --}}
                    @can('role.view')
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ in_array(Route::currentRouteName(), ['roles.index', 'roles.create', 'roles.edit',  'roles.edit.permissions']) ? 'active' : '' }}" href="{{ route('roles.index') }}">
                            <i class="nav-main-link-icon fa-solid fa-user-lock"></i><span class="nav-main-link-name">Roles</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
        </ul>
        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- END Sidebar Scrolling -->
    </nav>
    <!-- END Sidebar -->
