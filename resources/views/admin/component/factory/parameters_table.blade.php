<section class="box-fancy section-fullwidth text-light p-b-0">
    @if(count($devFactory->device->deviceType->deviceParameters) != 0)
        <div class="row" style="text-align: -webkit-center;">
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
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

                        <div class="table-responsive">

                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{__('message.No.')}}</th>
                                    @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $parameter)
                                        <th>{{$parameter->name}}</th>
                                    @endforeach

                                    <th>{{__('message.time of read')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php

                                    $no=0;

                                @endphp
                                @foreach($devFactory->deviceFactoryValues()->orderBy('id','desc')->get() as $deviceParameter)
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        @if($devFactory->device->deviceType != null)
                                            @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $parameter)
                                                @if(isset(json_decode($deviceParameter->parameters,true)[$parameter->code]))
                                                    <td>{{json_decode($deviceParameter->parameters,true)[$parameter->code]}}</td>
                                                @else
                                                    <td>0</td>
                                                @endif
                                            @endforeach
                                        @endif
                                        <td>
                                            {{\Carbon\Carbon::parse($deviceParameter->time_of_read)->setTimezone('Europe/Istanbul')->format('Y-d-m h:i a')}}
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
    @endif

</section>
@push('scripts')
    <script>

    </script>
@endpush
