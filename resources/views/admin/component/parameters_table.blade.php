<section class="box-fancy section-fullwidth text-light p-b-0">
    @if($device_type && $device_type->deviceParameters->isNotEmpty())
        <div class="row" style="text-align: -webkit-center;">
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                                    <span
                                        class="card-label font-weight-bolder text-dark"
                                        style="float: left;margin-bottom: 10px;">{{__('message.Parameters')}} </span>
                        </h3>
                        <a title="Export" id="d_{{$device->id}}" href="{{route('admin.devices.export', [$device->id])}}"
                           class="btn btn-edit btn-sm"
                           style="float: right;background-color: #00989d!important;"> {{__('message.Export To Data Sheet')}} </a>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th>{{__('message.No.')}}</th>
                                                @php
                                                    $tableColumns = ($parameterTableColumn && $parameterTableColumn->count() > 0)
                                                        ? $parameterTableColumn
                                                        : $device_type->deviceParameters;
                                                @endphp
                                                @foreach($tableColumns as $parameter)
                                                    <th>{{$parameter->name}}</th>
                                                @endforeach
                                                <th>{{__('message.time of read')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $no=0;
                                            @endphp
                                            @foreach($parameterTableData as $deviceParameter)
                                                <tr>
                                                    <td>{{ ++$no }}</td>
                                                    @foreach($tableColumns as $parameter)
                                                        @php
                                                            $values = $deviceParameter->decoded_parameters ?? [];
                                                            $code = $parameter->code ?? $parameter->name;
                                                            $fallback = $parameter->name ?? $code;
                                                            $value = $values[$code] ?? ($values[$fallback] ?? 0);
                                                        @endphp
                                                        <td>{{$value}}</td>
                                                    @endforeach
                                                    <td>
                                                        {{ optional($deviceParameter->time_of_read)->setTimezone('GMT+03:00')->format('Y-m-d H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
@push('scripts')
    <script>
        function test(x) {
            return x.toLocaleString()
        }
    </script>
@endpush
