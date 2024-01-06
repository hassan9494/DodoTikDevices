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

        #chart {
            max-width: 100%;
            margin: 35px auto;
        }

        .spinner-border {
            position: relative;
            bottom: 300px;
            width: 5rem;
            height: 5rem;
            display: none;
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
            <div class="col-md-12">
                @include('admin.component.files.flowchart')
            </div>
            <div class="col-md-12">
                @include('admin.component.files.parameters_table')
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>


@endpush
