@extends('layouts.admin')

@section('styles')

    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

    <style>
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

        .spinner-border {
            position: relative;
            bottom: 300px;
            width: 5rem;
            height: 5rem;
            display: none;
        }

        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }

        .tooltip-custom:before {
            content: "•";
            font-size: 170%; /* or whatever */
            padding-right: 5px;
        }
    </style>

@endsection

@section('content')

    <!-- Page Heading -->

{{--    <h1 class="h3 mb-2 text-gray-800">{{__('message.Factories')}}</h1>--}}

    @if (session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    @include('admin.component.factory.flowchart')
    @include('admin.component.factory.linechart')
    @include('admin.component.factory.parameters_table')

@endsection

@push('scripts')

    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>

@endpush
