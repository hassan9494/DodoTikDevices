@extends('layouts.admin')
@section('styles')

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Roboto, sans-serif;
        }
        .sticky {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
        }
        .sticky ul{
            position: fixed;
            top: 0;
            right: 0;
            z-index: 999;
        }

        /*.sticky + .content {*/
        /*    padding-top: 102px;*/
        /*}*/
        #chart {
            max-width: 100%;
            margin: 35px auto;
        }

        #map {
            width: 100%;
            height: 400px;
        }

        .spinner-border {
            position: relative;
            bottom: 300px;
            width: 5rem;
            height: 5rem;
            display: none;
        }
    </style>
    <style>
        .coordinates {
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            position: absolute;
            bottom: 40px;
            left: 10px;
            padding: 5px 10px;
            margin: 0;
            font-size: 11px;
            line-height: 18px;
            border-radius: 3px;
            display: none;
        }

        .legend {
            background-color: #fff;
            border-radius: 3px;
            top: 30px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            padding: 10px;
            position: absolute;
            right: 10px;
            z-index: 1;
        }

        .legend h4 {
            margin: 0 0 10px;
        }

        .legend div {
            text-align: left;
        }

        .legend div p {
            border-radius: 50%;
            display: inline-block;
            height: 10px;
            margin-right: 5px;
            width: 10px;
        }


        .legend-2 {
            background-color: #fff;
            border-radius: 3px;
            top: 30px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            padding: 10px;
            position: absolute;
            left: 10px;
            z-index: 1;
        }

        .legend-2 h4 {
            margin: 0 0 10px;
        }

        .legend-2 div {
            text-align: left;
        }

        .legend-2 div span {
            border-radius: 50%;
            display: inline-block;
            height: 10px;
            margin-right: 5px;
            width: 10px;
        }

        .mapboxgl-popup {
            max-width: 500px;
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
        }

        .apexcharts-tooltip-text-y-label {
            color: #000000;
        }

        .apexcharts-tooltip-text-y-value {
            color: #000000;
        }

        .apexcharts-tooltip-title {
            color: #000000;
        }

        #chartdiv {
            width: 100%;
            height: 500px;
        }

        #chart-container {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .dataTables_length {
            float: left;
        }

        .dataTables_length label {
            color: #0b2e13;
        }

        /*.dataTables_filter {*/
        /*     float: left;*/
        /* }*/
        .dataTables_filter label {
            color: #0b2e13;
        }

        /*.flowchart-nav .active{*/
        /*    pointer-events:none!important;*/
        /*}*/

    </style>

@endsection
@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
{{--                        <div class="col-md-12">--}}
{{--                            @include('admin.component.temperatureGauge')--}}
{{--                        </div>--}}
            @if(count($deviceComponents) > 0)
                @foreach($deviceComponents as $component)
                    <div class="col-md-{{$component->width}}">
                        @include('admin.component.'.$component->component->slug)
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection


@push('scripts')

    <script>
        window.onscroll = function() {myFunction()};

        var header = document.getElementById("myHeader");
        var sticky = header.offsetTop;

        function myFunction() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
        }
    </script>
    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>


@endpush
