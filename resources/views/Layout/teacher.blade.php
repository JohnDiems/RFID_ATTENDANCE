<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{asset('images/RFIDLOGO.png')}}">
    <title>Teacher Panel</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/teacher.css') }}" rel="stylesheet">
    <link href="{{ asset('css/TeacherStudent.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Announcement.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
    <link href="{{ asset('css/Sectionlist.css') }}" rel="stylesheet">
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">


    <link href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion text-dark Sd-bar" id="accordionSidebar"
            style="background-color: #337AB7; ">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center">
                <div>
                    <img class="img-profile rounded-circle" style="max-width: 60%;"
                        src="data:image/png;base64,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">
                </div>
                <div class="sidebar-brand-text mx-4" style="color: #fff">Assumption<sup
                        style="color: #2ECC71; "><span>ENTRIX</sup></span></div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('TeacherDashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt" style="font-size: 26px;"></i>
                    <span>Dashboard</span></a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading" style="color: white;">
                User Interface
            </div>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-user-check"></i>
                    <span class="font-weight-bold">MANAGE STUDENTS</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('TeacherStudentlist') }}">Student Profile</a>
                    </div>
                </div>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSection"
                    aria-expanded="true" aria-controls="collapseSection">
                    <i class="fas fa-fw fa-user-cog"></i>
                    <span class="font-weight-bold">MANAGE SECTION</span>
                </a>
                <div id="collapseSection" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('SectionTeacher') }}">Section List</a>
                        <div class="collapse-divider"></div>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseRecord"
                    aria-expanded="true" aria-controls="collapseRecord">
                    <i class="fas fa-fw fa-user-check"></i>
                    <span class="font-weight-bold">MANAGE RECORD</span>
                </a>
                <div id="collapseRecord" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('TeacherAttendanceRecord') }}">Regular Attendance</a>
                        <a class="collapse-item" href="{{ route('TeacherLunchRecord') }}">Lunch Attendance</a>
                    </div>
                </div>
            </li>




            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="background-color: #F8F9FC;">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top "
                    style=" background-color: #CAE4FD;">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <h3 style="margin-left: 20px; color:#858796"><span class="Welcome"></h3>
                    <!-- Topbar Search -->
                    {{-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
              <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                  </button>
                </div>
              </div>
            </form> --}}


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">



                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">

                                    @if (isset($user))
                                        <span
                                            class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $user->name }}</span>
                                    @endif

                                </span>
                                <img class="img-profile rounded-circle"
                                    src="data:image/png;base64,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">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('TeacherChange') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Change Password
                                </a>
                                <a class="dropdown-item" href="{{ route('TeacherActivity') }}">
                                    <i class="fas fa-heartbeat fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal"
                                    data-target="#logoutModal">
                                    {{-- <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> --}}
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
            <!-- End of Topbar -->


            <!-- Scroll to Top Button-->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>


            <!-- Logout Modal-->
            <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn text-white" data-dismiss="modal"
                                style="background-color: #337AB7">Cancel</button>
                            <form action="{{ route('Teacherlogout') }}" method="POST">
                                @csrf
                                <button type="submit" name="logout_btn"
                                    class="btn btn-danger text-white">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Logout Modal-->
            </div>

            <!--main content-->

            <div class="container-fluid">
                @yield('content')
            </div>

            <!--main content-->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="text-center">
                        <span>© 2023 Assumption ENTRIX. All rights reserved.</span>
                    </div>
                </div>
            </footer>
        </div>






        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
        <script src="{{ asset('js/teachertable.js') }}"></script>
        <script src="{{ asset('js/table.js') }}"></script>
        <script src="{{ asset('js/typeWelcome.js') }}"></script>
        <script src="{{ asset('js/type.js') }}"></script>
        <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

        <!-- Include Bootstrap Datepicker JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/vfs_fonts.js"></script>


</body>

</html>
