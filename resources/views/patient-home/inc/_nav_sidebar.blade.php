<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
    <!-- TODO add active page hightligh if the current page is users hightlight the uses list link in blue...-->
    <!-- TODO add insert btn on hover on patient/user list ifthey are being hoverd -->
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- logout button -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link logout" href="{{ route('patient.logout') }}" role="button" title="logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>


</nav>
<!-- /.navbar -->

<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-light-primary">
    <!-- Brand Logo-->
    <a href="#" class="brand-link">
        <img src="{{ asset('media/favicons/mhealth-192x192.png') }}" alt="AI Mental Health logo"
            class="brand-image img-circle elevation-3" style="max-height: 38px;opacity: .8">
        <span class="brand-text font-weight-light" style="font-size: 1.1rem;"><b>AVi</b>{{ env('APP_NAME') }}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            {{ Auth::guard('patient')->user()->full_name }}
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('appointment.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-notes-medical"></i>
                        <p>Citas</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
</aside>
<!-- /.sidebar -->
