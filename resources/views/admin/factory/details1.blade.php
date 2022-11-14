@extends('layouts.admin')

@section('styles')

    <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

@endsection

@section('content')

    <!-- Page Heading -->

{{--    <h1 class="h3 mb-2 text-gray-800">{{__('message.Factories')}}</h1>--}}

    @if (session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif

    <!-- DataTales Example -->

    <div class="card shadow mb-4">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                                    <span
                                        class="card-label font-weight-bolder text-dark"
                                        style="float: left;margin-bottom: 10px;">{{__('message.Parameters')}} </span>
            </h3>

            <a title="Export" id="d_{{$devFactory->id}}" href="{{route('admin.factories.export', $devFactory->id)}}"
               class="btn btn-edit btn-sm"
               style="float: right;background-color: #00989d!important;"> {{__('message.Export To Data Sheet')}} </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered" id="posts">
                        <thead>
                        <tr>
                            <th>{{__('message.No.')}}</th>
                            @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $parameter)
                                <th>{{$parameter->name}}</th>
                            @endforeach

                            <th>{{__('message.time of read')}}</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>


        </div>

    </div>

@endsection

@push('scripts')

    <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#posts').DataTable({
                "processing": true,
                "serverSide": true,
                "ordering": true,
                "ajax":{
                    "url": "{{ route('admin.factories.detail',$devFactory->id) }}",
                    "dataType": "json",
                    "type": "POST",
                    "data":{ _token: "{{csrf_token()}}"}
                },
                "columns": [
                    { "data": "No" , name: 'primary_key',sortable: true, "orderable": true, searchable: true, visible: true},
                    @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $parameter)
                    { "data": "{{$parameter->name}}" , "orderable": true, searchable: true, visible: true},
                    @endforeach
                    { "data": "time_of_read" , "orderable": true, searchable: true, visible: true}
                ],
                "order":[[1, 'desc']]

            });
        });
    </script>

    <script>
        var timer = {!! json_encode($devFactory->device, JSON_HEX_TAG) !!};
        // console.log(timer['time_between_two_read'] *1000 *60)
        interval = setInterval(function() {
            $('#posts').DataTable().ajax.reload(null, false)
        }, timer['time_between_two_read'] *1000 *60);
    </script>

@endpush
