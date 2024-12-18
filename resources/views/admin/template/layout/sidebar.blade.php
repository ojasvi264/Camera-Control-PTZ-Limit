<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{asset('backend/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('backend/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item menu-open">
                    <a href="{{ route('admin.index') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('/') ? "active" : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                <li class="nav-item menu-open">
                    <a href="{{ route('admin.control_system') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('control-system') ? "active" : '' }}">
                        <i class="nav-icon fas fa-camera"></i>
                        <p>
                            Control System
                        </p>
                    </a>
                </li>

{{--                <li class="nav-item menu-open">--}}
{{--                    <a href="{{ route('admin.ptz_setting') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('camera-setting') ? "active" : '' }}">--}}
{{--                        <i class="nav-icon fas fa-cog"></i>--}}
{{--                        <p>--}}
{{--                            PTZ Setting--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                </li>--}}

                <li class="nav-item" id="ptz">
                    <a href="#" class="nav-link {{ \Illuminate\Support\Facades\Request::is('camera-setting') ? "active" : '' }} {{ \Illuminate\Support\Facades\Request::is('camera-setting/*') ? "active" : '' }}" id="ptzSetting">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            PTZ Setting
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('admin.ptz_setting') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('camera-setting') ? "active" : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Set PTZ Limit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.ptz_setting.list') }}" class="nav-link {{ \Illuminate\Support\Facades\Request::is('camera-setting/list') ? "active" : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>PTZ Limit List</p>
                            </a>
                        </li>
                    </ul>
                </li>


{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link">--}}
{{--                        <i class="nav-icon far fa-envelope"></i>--}}
{{--                        <p>--}}
{{--                            Mailbox--}}
{{--                            <i class="fas fa-angle-left right"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="pages/mailbox/mailbox.html" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Inbox</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="pages/mailbox/compose.html" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Compose</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="pages/mailbox/read-mail.html" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Read</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<script>
    let ptzSettingTabStatus = document.getElementById('ptzSetting');
    let ptz = document.getElementById('ptz');

    if (ptzSettingTabStatus.classList.contains('active')) {
        ptz.classList.add('menu-open');
    } else {
        ptz.classList.remove('menu-open');
    }
</script>

