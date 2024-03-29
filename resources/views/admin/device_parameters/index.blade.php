@extends('layouts.admin')

@section('styles')

<link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

@endsection

@section('content')

<!-- Page Heading -->

<h1 class="h3 mb-2 text-gray-800">{{__('message.device_parameters')}}</h1>

@if (session('success'))

<div class="alert alert-success">

    {{ session('success') }}

</div>

@endif

<!-- DataTales Example -->

<div class="card shadow mb-4">
@can('isAdmin')
    <div class="card-header py-3">
        <a href="{{ route('admin.device_parameters.create') }}" class="btn btn-pass">{{__('message.add_new')}}</a>
    </div>
@endcan
    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{__('message.No.')}}</th>

                        <th>{{__('message.Name')}}</th>

                        <th>{{__('message.Code')}}</th>

                        <th>{{__('message.Unit')}}</th>

                        <th>{{__('message.Option')}}</th>

                    </tr>

                </thead>

                <tbody>

                @php

                $no=0;

                @endphp

                @foreach ($parameters as $parameter)
                @if(auth()->user()->role=='Administrator')
                    <tr>
                        <td>{{ ++$no }}</td>
                        <td>{{ $parameter->name }}</td>
                        <td>{{ $parameter->code }}</td>
                        <td>{{ $parameter->unit }}</td>
                        <td>
                            <a href="{{route('admin.device_parameters.edit', [$parameter->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-edit"></i> </a>
                            <a href="{{route('admin.device_parameters.color', [$parameter->id])}}" class="btn btn-edit btn-sm"> <i class="fas fa-eye-dropper"></i> </a>

                            <form method="POST" action="{{route('admin.device_parameters.destroy', [$parameter->id])}}" class="d-inline" onsubmit="return confirm('{{__("message.Delete this type permanently?")}}')">

                                @csrf

                                <input type="hidden" name="_method" value="DELETE">

                                <button type="submit" value="Delete" class="btn btn-delete btn-sm">
                                <i class='fas fa-trash-alt'></i>
                                </button>

                            </form>

                        </td>

                    </tr>
                    @endif
                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>

<script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>

@endpush
