<div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
    <div class="card card-custom mb-4">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label font-weight-bolder text-dark">{{ __('message.Devices Status') }}</span>
            </h3>
            @if($types->isNotEmpty())
                <div class="card-toolbar">
                    <nav>
                        <div class="nav nav-tabs nav-fill device-types-name" id="nav-tab" role="tablist">
                            @foreach($types as $type)
                                <a class="nav-item nav-link {{ $loop->first ? 'active' : '' }}"
                                   id="nav-{{ Str::slug($type->name) }}-tab"
                                   data-toggle="tab"
                                   href="#nav-{{ Str::slug($type->name) }}"
                                   role="tab"
                                   aria-controls="nav-{{ Str::slug($type->name) }}"
                                   aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                    <span>{{ $type->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </nav>
                </div>
            @endif
        </div>
        <div class="card-body pt-2" style="position: relative;">
            <div class="row">
                <div class="tab-content" id="nav-tabContent" style="width: 100%">
                    @forelse($types as $type)
                        @php
                            $devicesForType = $devicesByType[$type->id] ?? [];
                        @endphp
                        <div class="tab-pane fade {{ $loop->first ? 'active show' : '' }}"
                             id="nav-{{ Str::slug($type->name) }}"
                             role="tabpanel"
                             aria-labelledby="nav-{{ Str::slug($type->name) }}-tab">
                            <div class="row">
                                @forelse($devicesForType as $device)
                                    <div class="col-md-4" style="margin-top: 15px;margin-bottom: 15px">
                                        <div class="card card-custom mb-4">
                                            <div class="card-header border-0 pt-5" style="padding-top: 1rem!important;">
                                                <h3 class="card-title align-items-start flex-column">
                                                    <span class="card-label font-weight-bolder text-dark">{{ $device['name'] }}</span>
                                                </h3>
                                                <div class="card-toolbar">
                                                    <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">
                                                        <li class="nav-item nav-item">
                                                            <a title="Show" id="d_{{ $device['id'] }}"
                                                               href="{{ route('admin.devices.show', [$device['id']]) }}"
                                                               class="btn btn-sm">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-body pt-2"
                                                 style="background-color: {{ $device['status'] === 'Offline' ? '#ff6464' : '#00989d' }}; padding-top: 2rem!important;">
                                                <h4 class="device-status" style="color: #FFFFFF">{{ $device['status'] }}</h4>
                                                <i class="fas {{ $device['status'] === 'Offline' ? 'fa-times' : 'fa-check' }}"
                                                   style="font-size: 25px; color: {{ $device['status'] === 'Offline' ? 'red' : 'green' }}"></i>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-md-12">
                                        <h3 class="card-title align-items-start flex-column no-device-in-type">
                                            <span class="card-label font-weight-bolder text-dark">{{ __('message.No Devices In This Type') }}</span>
                                        </h3>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12">
                            <h3 class="card-title align-items-start flex-column no-device-in-type">
                                <span class="card-label font-weight-bolder text-dark">{{ __('message.No Devices In This Type') }}</span>
                            </h3>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>




@push('scripts')
    <script>
        const tabs = document.querySelector('#nav-tab');
        if (tabs) {
            tabs.addEventListener('shown.bs.tab', () => {
                window.dispatchEvent(new Event('resize'));
            });
        }
    </script>
@endpush
