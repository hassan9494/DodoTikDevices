<div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
    <div class="card card-custom mb-4">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Devices Status')}}</span>
            </h3>
            <div class="card-toolbar">
                <nav>
                    <div class="nav nav-tabs nav-fill device-types-name" id="nav-tab" role="tablist">
                        @foreach($types as $type)
                            <a class=" nav-item nav-link {{$type->id == $types[0]->id ? "active":""}}"
                               id="nav-{{\Str::slug($type->name)}}-tab" data-toggle="tab" href="#nav-{{\Str::slug($type->name)}}" role="tab"
                               aria-controls="nav-{{\Str::slug($type->name)}}" aria-selected="true"><span>{{$type->name}}</span></a>
                        @endforeach
                    </div>
                </nav>

            </div>
        </div>
        <div class="card-body pt-2" style="position: relative;">
            <div class="row">
                <div class="tab-content" id="nav-tabContent" style="width: 100%">
                    @foreach($types as $type)
                        <div class="tab-pane fade  {{$type->id == $types[0]->id ? "active show":""}}" id="nav-{{\Str::slug($type->name)}}"
                             role="tabpanel" aria-labelledby="nav-{{$type->slug}}-tab">
                            <div class="row">
                                @foreach($devices as $key=>$device)
                                    {{--                                @php--}}
                                    {{--                                    $item = \App\Models\Product::find($item->id)--}}
                                    {{--                                @endphp--}}
                                    @if($device->type_id == $type->id)
                                        <div class="col-md-4 " style="margin-top: 15px;margin-bottom: 15px">
                                            <div class="card card-custom mb-4">
                                                <div class="card-header border-0 pt-5" style="padding-top: 1rem!important;">
                                                    <h3 class="card-title align-items-start flex-column"><span
                                                            class="card-label font-weight-bolder text-dark">{{$device->name}}</span>
                                                    </h3>
                                                    <div class="card-toolbar">
                                                        <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test"
                                                            role="tablist">
                                                            <li class="nav-item nav-item">
                                                                <a title="Show" id="d_{{$device->id}}"
                                                                   href="{{route('admin.devices.show', [$device->id])}}"
                                                                   class="btn btn-sm"> <i class="fas fa-eye"></i> </a>
                                                            {{--                                                    <a title="Edit" href="{{route('admin.devices.edit', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-edit"></i> </a>--}}
                                                            {{--                                                    <a href="{{route('admin.devices.add_device_setting_values', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-cogs"></i> </a>--}}
                                                            {{--                                                    <a href="{{route('admin.devices.add_device_limit_values', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-chart-line"></i> </a>--}}
                                                            {{--                                                    <a title="Edit Location" href="{{route('admin.devices.location', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-location-arrow"></i> </a>--}}
                                                            <li class="nav-item nav-item">
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body  pt-2"
                                                     style="background-color: {{$state[$key] == 'Offline' ? '#ff6464' : '#00989d'}} ;    padding-top: 2rem!important;">
                                                    <h4 class="device-status" style="color: #FFFFFF">{{$state[$key]}} </h4><i
                                                        class="fas {{$state[$key] == "Offline" ? 'fa-times' : 'fa-check'  }}"
                                                        style="font-size: 25px; color:{{$state[$key] == "Offline" ? 'red' : 'green'  }} "></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                @endforeach

                            </div>
                            @can('isAdmin')
                            @if(count($type->devices) == 0 )
                                <h3 class="card-title align-items-start flex-column no-device-in-type">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.No Devices In This Type')}}</span>
                                </h3>
                            @endif
                            @endcan
                            @can('isUser')
                                @if(count($type->devices()->where('user_id',Auth::user()->id)->get()) == 0 )
                                    <h3 class="card-title align-items-start flex-column no-device-in-type">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.No Devices In This Type')}}</span>
                                    </h3>
                                @endif
                            @endcan
                        </div>

                    @endforeach
                </div>
{{--                @foreach($devices as $key=>$device)--}}
{{--                    <div class="col-md-4 " style="margin-top: 15px;margin-bottom: 15px">--}}
{{--                        <div class="card card-custom mb-4">--}}
{{--                            <div class="card-header border-0 pt-5" style="padding-top: 1rem!important;">--}}
{{--                                <h3 class="card-title align-items-start flex-column"><span--}}
{{--                                        class="card-label font-weight-bolder text-dark">{{$device->name}}</span>--}}
{{--                                </h3>--}}
{{--                                <div class="card-toolbar">--}}
{{--                                    <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test"--}}
{{--                                        role="tablist">--}}
{{--                                        <li class="nav-item nav-item">--}}
{{--                                            <a title="Show" id="d_{{$device->id}}"--}}
{{--                                               href="{{route('admin.devices.show', [$device->id])}}"--}}
{{--                                               class="btn btn-sm"> <i class="fas fa-eye"></i> </a>--}}
{{--                                        --}}{{--                                                    <a title="Edit" href="{{route('admin.devices.edit', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-edit"></i> </a>--}}
{{--                                        --}}{{--                                                    <a href="{{route('admin.devices.add_device_setting_values', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-cogs"></i> </a>--}}
{{--                                        --}}{{--                                                    <a href="{{route('admin.devices.add_device_limit_values', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-chart-line"></i> </a>--}}
{{--                                        --}}{{--                                                    <a title="Edit Location" href="{{route('admin.devices.location', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-location-arrow"></i> </a>--}}
{{--                                        <li class="nav-item nav-item">--}}
{{--                                    </ul>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="card-body  pt-2"--}}
{{--                                 style="background-color: {{$state[$key] == 'Offline' ? '#ff6464' : '#00989d'}} ;    padding-top: 2rem!important;">--}}
{{--                                <h4 class="device-status" style="color: #FFFFFF">{{$state[$key]}} </h4><i--}}
{{--                                    class="fas {{$state[$key] == "Offline" ? 'fa-times' : 'fa-check'  }}"--}}
{{--                                    style="font-size: 25px; color:{{$state[$key] == "Offline" ? 'red' : 'green'  }} "></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
            </div>
        </div>

    </div>
</div>
