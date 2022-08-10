<section class="box-fancy section-fullwidth text-light p-b-0">
    @if(count($device->deviceType->deviceSettings) != 0)
        <div class="row" style="text-align: -webkit-center;">
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                <div class="card card-custom mb-4">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.settings')}} </span>
                        </h3>


                    </div>


                    <div class="card-body pt-2" style="position: relative;">
                        <div class="col-md-12">
                            <div class="card shadow mb-4">


                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            @foreach($device->deviceType->deviceSettings as $setting)
                                                <th>{{$setting->name}}</th>
                                            @endforeach
                                            <th>{{__('message.Option')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>

                                            @if($device->deviceSetting == null)
                                                @foreach($device->deviceType->deviceSettings as $setting)
                                                    <td>{{$setting->pivot->value}}</td>
                                                @endforeach
                                            @else
                                                @foreach($device->deviceType->deviceSettings as $setting)
                                                    <td>{{json_decode($device->deviceSetting->settings,true)[$setting->code] }}</td>
                                                @endforeach
                                            @endif
                                            <td>
                                                <a href="{{route('admin.devices.add_device_setting_values', [$device->id])}}"
                                                   class="btn btn-edit btn-sm"> <i class="fas fa-cogs"></i> </a>

                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>
