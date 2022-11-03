@extends('layouts.documentation')

@section('styles')


@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

{{--    @include('documentation.sections.welcome', [])--}}

    @include('documentation.sections.getting_started', [])

    @include('documentation.sections.device_type', [])

    @include('documentation.sections.device', [])

{{--    @include('documentation.sections.lines.line_1', [])--}}

    @include('documentation.sections.type_parameters', [])

    @include('documentation.sections.type_settings', [])

    @include('documentation.sections.component', [])

{{--    @include('documentation.sections.device_component', [])--}}

{{--    @include('documentation.sections.component_settings', [])--}}

{{--    @include('documentation.sections.users', [])--}}



@endsection

@push('scripts')
@endpush
