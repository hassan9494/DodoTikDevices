<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Admin - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;1,400&display=swap"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('admin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    {{-- Summernote CDN --}}

    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

    {{-- Select2 Style CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet"/>
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://unpkg.com/gojs@2.2.14/release/go.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

    @yield('styles')

    <style>
        .unread {
            background-color: #e5e5e5;
        }
    </style>
</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}" style="margin-bottom: 25px">
            <img height="170" src="{{ asset('admin/img/logoDodo.png')}}" class="attachment-medium size-medium" alt=""
                 loading="lazy" srcset="{{ asset('admin/img/logoDodo.png')}}" sizes="(max-width: 200px) 100vw, 207px" style="margin-top: 20px">
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>{{__('message.Dashboard')}}</span></a>
        </li>
    @can('isAdmin')
        <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.device_types') }}">
                    <i class="fas fa-fw fa-keyboard"></i>
                    <span>{{__('message.device_types')}}</span></a>
            </li>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.device_parameters') }}">
                    <i class="fas fa-fw fa-temperature-high"></i>
                    <span>{{__('message.type_parameters')}}</span></a>
            </li>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.device_setting') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>{{__('message.type_setting')}}</span></a>
            </li>


            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.devices') }}">
                    <i class="fas fa-fw fa-satellite-dish"></i>
                    <span>{{__('message.devices')}}</span></a>
            </li>

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.components.index') }}">
                    <i class="fas fa-fw fa-satellite-dish"></i>
                    <span>{{__('message.Components')}}</span></a>
            </li>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.device_components.index') }}">
                    <i class="fas fa-fw fa-satellite-dish"></i>
                    <span>{{__('message.Device_Components')}}</span></a>
            </li>


            <!-- Nav Item - Dashboard -->
{{--            <li class="nav-item active">--}}
{{--                <a class="nav-link" href="{{ route('admin.component_settings') }}">--}}
{{--                    <i class="fas fa-fw fa-satellite-dish"></i>--}}
{{--                    <span>{{__('message.Components_Settings')}}</span></a>--}}
{{--            </li>--}}


            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.factories') }}">
                    <i class="fas fa-fw fa-industry"></i>
                    <span>{{__('message.Factories')}}</span></a>
            </li>
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.files') }}">
                    <i class="fas fa-fw fa-file-csv"></i>
                    <span>{{__('message.Files')}}</span></a>
            </li>
    @endcan
    <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="{{ route('admin.devices.create') }}">
                <i class="fas fa-fw fa-plus"></i>
                <span>{{__('message.add_new_devices')}}</span></a>
        </li>
        @can('isUser')
            <li class="nav-item active">
                <a class="nav-link  collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                   aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-satellite-dish"></i>
                    <span>{{__('message.devices')}}</span></a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded"  style="background-color: #00989d!important;">
                        @foreach($all_devices as $all_device)
                            <a class="collapse-item"
                               href="{{route('admin.devices.show', [$all_device->id])}}" style="color: #fff;">{{$all_device->name}}</a>
                        @endforeach
                    </div>
                </div>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.devices.get_devices') }}">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>{{__('message.settings')}}</span></a>
            </li>
        @endcan
{{--        <li class="nav-item">--}}
{{--            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne1"--}}
{{--               aria-expanded="true" aria-controls="collapseOne">--}}
{{--                <i class="fas fa-fw fa-table"></i>--}}
{{--                <span>{{__('message.Documentation')}}</span>--}}
{{--            </a>--}}
{{--            <div id="collapseOne1" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">--}}
{{--                <div class="bg-white py-2 collapse-inner rounded">--}}
{{--                    <a class="collapse-item" href="{{ route('admin.documentaion') }}">{{__('message.Gateway')}}</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </li>--}}

        <li class="nav-item active">
            <a class="nav-link" href="{{ route('admin.documentaion') }}" target="_blank">
                <i class="fas fa-fw fa-file"></i>
                <span>{{__('message.Documentaion')}}</span></a>
        </li>

    <!-- Nav Item - Pages Collapse Menu -->
        @can('isAdmin')
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                   aria-expanded="true" aria-controls="collapseOne">
                    <i class="fas fa-fw fa-table"></i>
                    <span>{{__('message.Management')}}</span>
                </a>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('admin.users.index') }}">{{__('message.users')}}</a>
                    </div>
                </div>
            </li>
        @endcan
        {{--        @can('isAdmin')--}}
        {{--        <!-- Nav Item - Utilities Collapse Menu -->--}}
        {{--        <li class="nav-item">--}}
        {{--            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"--}}
        {{--               aria-expanded="true" aria-controls="collapseUtilities">--}}
        {{--                <i class="fas fa-fw fa-wrench"></i>--}}
        {{--                <span>{{__('message.Settings')}}</span>--}}
        {{--            </a>--}}
        {{--            <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"--}}
        {{--                 data-parent="#accordionSidebar">--}}
        {{--                <div class="bg-white py-2 collapse-inner rounded">--}}
        {{--                    <a class="collapse-item" href="{{ route('admin.about') }}">{{__('message.About')}}</a>--}}
        {{--                    <a class="collapse-item" href="{{ route('admin.general') }}">{{__('message.General Settings')}}</a>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </li>--}}
        {{--        <!-- Divider -->--}}
        {{--            @endcan--}}
        <hr class="sidebar-divider d-none d-md-block">


        <li class="nav-item active">
            <a class="nav-link" href="{{ route('admin.testData') }}" target="_blank">
                <i class="fas fa-fw fa-file"></i>
                <span>{{__('message.Test Data')}}</span></a>
        </li>


        <hr class="sidebar-divider d-none d-md-block">
        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>
    </ul>
    <!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" id="myHeader">
                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>
                @if(isset($device) && isset($status))
                <h3 class="card-title align-items-start flex-column" style="margin-top: 5px;margin-left: 10px">
                                <span
                                    class="card-label font-weight-bolder text-dark" style="font-size: 1rem;">{{__('message.device_Name')}} : {{$device->name}} </span>
                    <br><span id="status"
                          class="card-label font-weight-bolder text-dark"
                          style="margin-top: 15px;font-size: 1rem;">{{__('message.status')}} : {{$status}}  <i
                            class="fas {{$status == "Offline" ? 'fa-times' : 'fa-check'  }}"
                            style="color:{{$status == "Offline" ? 'red' : 'green'  }} "></i> </span>
                </h3>
            @endif
                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                            <img class="img-profile rounded-circle" src="{{ asset('admin/img/admin.jpg')}}">
                        </a>

                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                             aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="{{route('admin.users.edit', [Auth::user()->id])}}">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{__('message.Account_Setting')}}
                            </a>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                {{__('message.Logout')}}
                            </a>
                        </div>

                    </li>
                </ul>
            </nav>
            <!-- End of Topbar -->
            <!-- Begin Page Content -->
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>{{__('message.Copyright')}} &copy; {{__('message.DodoTik')}} 2022</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('message.Ready to Leave?')}}</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div
                class="modal-body">{{__('message.Select "Logout" below if you are ready to end your current session.')}}</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('message.Cancel')}}</button>
                <a class="btn btn-primary" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('admin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('admin/js/sb-admin-2.min.js') }}"></script>


<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: "Choose Some"
        });
    });
    // $(function () {
    //     $('.selectpicker').selectpicker();
    // });
</script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="{{ asset('admin/js/summernote-image-title.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#summernote').summernote({
            fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana', 'Poppins'],
            fontNamesIgnoreCheck: ['Poppins'],
            imageTitle: {
                specificAltField: true,
            },
            lang: 'en-US',
            popover: {
                image: [
                    ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                    ['float', ['floatLeft', 'floatRight', 'floatNone']],
                    ['remove', ['removeMedia']],
                    ['custom', ['imageTitle']],
                ],
            },
        });
    });
    // notification count
    var count = $('#count'), c;
    c = parseInt(count.html());
    // count.html(c+1);
    // notification style
    $('.notification').on('click', function () {
        setTimeout(() => {
            count.html(0);
            $('.unread').each(function () {
                $(this).removeClass('unread');
            });
        }, 3000);
        //   $.get('MarkAllSeen', function(){});
    });
</script>


@stack('scripts')

</body>

</html>
