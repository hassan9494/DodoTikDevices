<!DOCTYPE html>
<!--[if lt IE 7 ]>
<html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
<!--<![endif]-->

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta content="{{ $general->meta_desc }}" name="description">
    <meta content="{{ $general->keyword }}" name="keywords">
    <meta name="author" content="">

    <title>Dodtik Device Documentation</title>

    <link rel="shortcut icon" href="{{ asset('storage/'.$general->favicon) }}" type="image/x-icon">

    @yield('meta')

    <link rel="stylesheet" type="text/css"
          href="{{ asset('documentation/fonts/font-awesome-4.3.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('documentation/css/stroke.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('documentation/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('documentation/css/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('documentation/css/prettyPhoto.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('documentation/css/style.css') }}">
    <link href="{{ asset('admin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <link rel="stylesheet" type="text/css" href="{{ asset('documentation/js/syntax-highlighter/styles/shCore.css') }}"
          media="all">
    <link rel="stylesheet" type="text/css"
          href="{{ asset('documentation/js/syntax-highlighter/styles/shThemeRDark.css') }}" media="all">

    <!-- CUSTOM -->
    <link rel="stylesheet" type="text/css" href="{{ asset('documentation/css/custom.css') }}">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>


<body>

<!-- ======= Header ======= -->
<button onclick="topFunction()" id="myBtn" title="Go to top"><i class="fa fa-chevron-up" aria-hidden="true"></i>
</button>

<script>
    var mybutton = document.getElementById("myBtn");
    window.onscroll = function () {
        scrollFunction()
    };

    function scrollFunction() {
        if (document.body.scrollTop > 1000 || document.documentElement.scrollTop > 1000) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    function topFunction() {
        window.scrollTo({top: 0, behavior: 'smooth'})
        document.documentElement.scrollTo({top: 0, behavior: 'smooth'})
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.querySelector('#mode').addEventListener('click', () => {
            document.querySelector('html').classList.toggle('dark');
        })
    });


</script>

<div id="wrapper">
    <div id="mode">
        <div class="dark">
            <svg aria-hidden="true" viewBox="0 0 512 512">
                <title>lightmode</title>
                <path fill="currentColor"
                      d="M256 160c-52.9 0-96 43.1-96 96s43.1 96 96 96 96-43.1 96-96-43.1-96-96-96zm246.4 80.5l-94.7-47.3 33.5-100.4c4.5-13.6-8.4-26.5-21.9-21.9l-100.4 33.5-47.4-94.8c-6.4-12.8-24.6-12.8-31 0l-47.3 94.7L92.7 70.8c-13.6-4.5-26.5 8.4-21.9 21.9l33.5 100.4-94.7 47.4c-12.8 6.4-12.8 24.6 0 31l94.7 47.3-33.5 100.5c-4.5 13.6 8.4 26.5 21.9 21.9l100.4-33.5 47.3 94.7c6.4 12.8 24.6 12.8 31 0l47.3-94.7 100.4 33.5c13.6 4.5 26.5-8.4 21.9-21.9l-33.5-100.4 94.7-47.3c13-6.5 13-24.7.2-31.1zm-155.9 106c-49.9 49.9-131.1 49.9-181 0-49.9-49.9-49.9-131.1 0-181 49.9-49.9 131.1-49.9 181 0 49.9 49.9 49.9 131.1 0 181z"></path>
            </svg>
        </div>
        <div class="light">
            <svg aria-hidden="true" viewBox="0 0 512 512">
                <title>darkmode</title>
                <path fill="currentColor"
                      d="M283.211 512c78.962 0 151.079-35.925 198.857-94.792 7.068-8.708-.639-21.43-11.562-19.35-124.203 23.654-238.262-71.576-238.262-196.954 0-72.222 38.662-138.635 101.498-174.394 9.686-5.512 7.25-20.197-3.756-22.23A258.156 258.156 0 0 0 283.211 0c-141.309 0-256 114.511-256 256 0 141.309 114.511 256 256 256z"></path>
            </svg>
        </div>
    </div>
    <div class="container-fluid">
        <section id="top" class="section docs-heading">

            <div class="row">
                <div class="col-md-12">
                    <div class="big-title text-center">
                        <h1>Dodtik Device Documentation</h1>
                        <p class="lead">The full tutorial to go in this website</p>
                    </div>
                    <!-- end title -->
                </div>
                <!-- end 12 -->
            </div>
            <!-- end row -->

            <hr>

        </section>
        <!-- end section -->

        <div class="row">
            <div class="col-md-3">
                <nav class="docs-sidebar" data-spy="affix" data-offset-top="300" data-offset-bottom="200"
                     role="navigation">
                    <ul class="nav">
                        <li><a href="#getting_started">Getting Started</a></li>
                        <li><a href="#device_type">Device Type</a>
                            <ul class="nav">
                                <li><a href="#device_type_1">Show Device Types</a></li>
                                <li><a href="#device_type_2">Create Device Type</a></li>
                                <li><a href="#device_type_3">Add Device Type Options</a></li>
                                <li><a href="#device_type_4">Edit Device Type</a></li>
                                <li><a href="#device_type_5">Delete Device Type</a></li>
                            </ul>
                        </li>
                        <li><a href="#device">Device</a>
                            <ul class="nav">
                                <li><a href="#device_1">Show Devices</a></li>
                                <li><a href="#device_2">Create Device</a></li>
                                <li><a href="#device_3">Change Setting</a></li>
                                <li><a href="#device_4">Limit Min & Max value</a></li>
                                <li><a href="#device_5">Edit Device</a></li>
                                <li><a href="#device_6">Delete Device</a></li>
                                <li><a href="#device_7">Change Device Location</a></li>
                                <li><a href="#device_8">Export Data To Excel Sheet</a></li>
                                <li><a href="#device_9">Change Device Page Show</a></li>
                                <li><a href="#device_10">Device Page Show</a></li>
                                <li><a href="#device_11">Connect Device To Website</a>
                                    <ul class="nav">
                                        <li><a href="#device_11_1">Gateway Device</a></li>
                                        <li><a href="#device_11_2">Simple Device</a></li>

                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li><a href="#type_parameters">Type Parameter</a>
                            <ul class="nav">
                                <li><a href="#type_parameters_1">Show Type Parameter</a></li>
                                <li><a href="#type_parameters_2">Create Type Parameter</a></li>
                                <li><a href="#type_parameters_3">Edit Type Parameter</a></li>
                                <li><a href="#type_parameters_4">Delete Type Parameter</a></li>
                            </ul>
                        </li>
                        <li><a href="#type_settings">Type Setting</a>
                            <ul class="nav">
                                <li><a href="#type_settings_1">Show Type Setting</a></li>
                                <li><a href="#type_settings_2">Create Type Setting</a></li>
                                <li><a href="#type_settings_3">Edit Type Setting</a></li>
                                <li><a href="#type_settings_4">Delete Type Setting</a></li>
                            </ul>
                        </li>
                        <li><a href="#component">Component</a>
                            <ul class="nav">
                                <li><a href="#component_1">Show Components</a></li>
{{--                                <li><a href="#component_2">Create Component</a></li>--}}
                                <li><a href="#component_3">Edit Component</a></li>
                                <li><a href="#component_4">Delete Component</a></li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
            <div class="col-md-9">
                @yield('content')
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('documentation/js/jquery.min.js') }}"></script>
<script src="{{ asset('documentation/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('documentation/js/retina.js') }}"></script>
<script src="{{ asset('documentation/js/jquery.fitvids.js') }}"></script>
<script src="{{ asset('documentation/js/wow.js') }}"></script>
<script src="{{ asset('documentation/js/jquery.prettyPhoto.js') }}"></script>

<!-- CUSTOM PLUGINS -->
<script src="{{ asset('documentation/js/custom.js') }}"></script>
<script src="{{ asset('documentation/js/main.js') }}"></script>

<script src="{{ asset('documentation/js/syntax-highlighter/scripts/shCore.js') }}"></script>
<script src="{{ asset('documentation/js/syntax-highlighter/scripts/shBrushXml.js') }}"></script>
<script src="{{ asset('documentation/js/syntax-highlighter/scripts/shBrushCss.js') }}"></script>
<script src="{{ asset('documentation/js/syntax-highlighter/scripts/shBrushJScript.js') }}"></script>

{{--<!-- Vendor JS Files -->--}}
{{--<script src="{{ asset('front/vendor/jquery/jquery.min.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/jquery.easing/jquery.easing.min.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/php-email-form/validate.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/jquery-sticky/jquery.sticky.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/venobox/venobox.min.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/waypoints/jquery.waypoints.min.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/owl.carousel/owl.carousel.min.js') }}"></script>--}}
{{--<script src="{{ asset('front/vendor/aos/aos.js') }}"></script>--}}

{{--<!-- Template Main JS File -->--}}
{{--<script src="{{ asset('front/js/main.js') }}"></script>--}}

{!! $general->tawkto !!}

@stack('scripts')

</body>

</html>


{{--<li><a href="#line2">How to Install WordPress</a></li>--}}
{{--<li><a href="#line3">How to Install Theme</a></li>--}}
{{--<li><a href="#line4">Necessary Plugins</a></li>--}}
{{--<li><a href="#line5">Creating Blog Pages</a></li>--}}
{{--<li><a href="#line6">Revolution Slider</a></li>--}}

{{--<li><a href="#line8">Support Desk</a></li>--}}
{{--<li><a href="#line9">Files & Sources</a></li>--}}
{{--<li><a href="#line10">Version History (Changelog)</a></li>--}}
{{--<li><a href="#line11">Copyright and license</a></li>--}}
