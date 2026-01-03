<section class="box-fancy section-fullwidth text-light p-b-0">
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark" style="font-size: 1rem;">{{__('message.Flow Chart')}}  </span>
{{--                        <span id="status"--}}
{{--                              class="card-label font-weight-bolder text-dark"--}}
{{--                              style="margin-top: 15px;font-size: 1rem;">{{__('message.status')}} : {{$status}}  <i--}}
{{--                                class="fas {{$status == "Offline" ? 'fa-times' : 'fa-check'  }}"--}}
{{--                                style="color:{{$status == "Offline" ? 'red' : 'green'  }} "></i> </span>--}}
                    </h3>

                    <div class="card-toolbar">
                        <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test flowchart-nav" role="tablist">
                            <li class="nav-item nav-item">
                                <a href="#" role="tab" data-rb-event-key="Custom"
                                   tabindex="3" aria-selected="false"
                                   class="nav-link py-2 px-4 nav-link {{$label == 2 ? "active" : ""}}"
                                   id="custom">{{__('message.Custom')}}</a></li>
                            <li class="nav-item nav-item">
                                <a href="#" role="tab"
                                   data-rb-event-key="ThisMonth"
                                   tabindex="2" aria-selected="false"
                                   class="nav-link py-2 px-4 nav-link {{$label == 30 ? "active" : ""}}">This
                                    {{__('message.Month')}} </a>
                            </li>
                            <li class="nav-item nav-item">
                                <a href="#" role="tab"
                                   data-rb-event-key="ThisWeek"
                                   tabindex="1" aria-selected="false"
                                   class="nav-link py-2 px-4 nav-link {{$label == 7 ? "active" : ""}}">This
                                    {{__('message.Week')}} </a>
                            </li>
                            <li class="nav-item nav-item">
                                <a href="#" role="tab"
                                   data-rb-event-key="ThisDay"
                                   aria-selected="true"
                                   tabindex="0" id="thisDay"
                                   class="nav-link py-2 px-4 nav-link {{$label == 1 ? "active" : ""}} ">{{__('message.This Day')}}</a></li>
                        </ul>
                    </div>

                </div>
                <div class="custom-date row" id="custom-date" style="display: {{$label == 2 ? "block" : "none"}}">

                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="from" class="form-label" style="color: #00989d">{{__('message.From')}}</label>
                                <input id="from" min="{{\Carbon\Carbon::now()->subMonth()->format("Y-m-d")}}"
                                       max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" type="date" name="from"
                                       class="form-control"
                                       value="{{\Carbon\Carbon::now()->format("Y-m-01")}}">
                            </div>
                            <div class="col-md-5">
                                <label for="to" class="form-label" style="color: #00989d">{{__('message.To')}}</label>
                                <input id="to" min="{{\Carbon\Carbon::now()->subMonth()->format("Y-m-d")}}"
                                       max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" type="date" name="to"
                                       class="form-control"
                                       value="{{\Carbon\Carbon::now()->format("Y-m-d")}}">
                            </div>
                            <div class="col-md-2">
                                <label for="to" class="form-label" style="color: #ffffff">get data</label>
                                <button class="btn btn-primary" onclick="getData()" style="color: #b5b5c3;background: #00989d;border-color: #00989d;">get data</button>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-2">
{{--                                <button class="btn btn-primary" onclick="test()">get data</button>--}}
                    </div>

                </div>

                <div class="card-body pt-2" style="position: relative;">
                    <div id="chart"></div>
                    <div class="spinner-border text-success" role="status" id="spinner">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@php
    $lineParameterSource = ($testPara ?? collect());
    if (blank($lineParameterSource) || $lineParameterSource->count() === 0) {
        $lineParameterSource = $device->deviceType
            ? $device->deviceType->deviceParameters()->orderBy('order')->get()
            : collect();
    }

    $lineParameterMeta = $lineParameterSource
        ->map(fn($parameter) => [
            'name' => $parameter->name ?? $parameter->code ?? 'Parameter',
            'unit' => $parameter->unit ?? null,
            'code' => $parameter->code ?? null,
        ])
        ->values();
@endphp

@push('scripts')
    <script>
        (function () {
            const parameterMeta = {!! $lineParameterMeta->toJson(JSON_HEX_TAG) !!};
            const initialSeries = {!! json_encode(array_values($paraValues), JSON_HEX_TAG) !!};
            const initialLabels = {!! json_encode(array_values($xValues), JSON_HEX_TAG) !!};
            const seriesColors = {!! json_encode(array_values($color), JSON_HEX_TAG) !!};
            const refreshIntervalMs = (Number({{ (int) ($device->time_between_two_read ?? 0) }}) || 0) * 60 * 1000;

            let chartInstance = null;
            let refreshTimer = null;
            let activeFrom = 1;
            let activeTo = 0;

            const spinner = jQuery('#spinner');
            spinner.hide();
            const customDateContainer = jQuery('#custom-date');

            function buildSeries(seriesData) {
                return parameterMeta.map(function (meta, index) {
                    const name = meta.name || meta.code || 'Parameter';
                    const unit = meta.unit ? ' (' + meta.unit + ')' : '';

                    return {
                        name: name + unit,
                        data: (seriesData[index] || []).map(function (value) {
                            return value === null ? 0 : Number(value);
                        })
                    };
                });
            }

            function renderChart(labels, seriesData, animate) {
                const preparedSeries = buildSeries(seriesData);
                const enableAnimations = animate ?? (labels.length < 500);

                const options = {
                    series: preparedSeries,
                    colors: seriesColors,
                    chart: {
                        height: 500,
                        width: '100%',
                        type: 'area',
                        animations: {
                            enabled: enableAnimations,
                        }
                    },
                    markers: {
                        size: 0,
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        width: 4,
                        curve: 'smooth',
                    },
                    xaxis: {
                        type: 'datetime',
                        labels: {
                            datetimeUTC: false,
                        },
                        categories: labels,
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left',
                        fontSize: '12px',
                        itemMargin: {
                            horizontal: 10,
                            vertical: 12,
                        },
                    },
                };

                if (!chartInstance) {
                    chartInstance = new ApexCharts(document.querySelector('#chart'), options);
                    chartInstance.render();
                } else {
                    chartInstance.updateOptions({
                        series: preparedSeries,
                        chart: options.chart,
                        xaxis: options.xaxis,
                    });
                }
            }

            function scheduleRefresh() {
                if (refreshTimer) {
                    clearInterval(refreshTimer);
                    refreshTimer = null;
                }

                if (activeFrom === 1 && activeTo === 0 && refreshIntervalMs > 0) {
                    refreshTimer = setInterval(function () {
                        fetchAndUpdate(activeFrom, activeTo, false);
                    }, refreshIntervalMs);
                }
            }

            function fetchAndUpdate(from, to, showSpinner = true) {
                if (showSpinner) {
                    spinner.show();
                }

                jQuery.ajax({
                    url: '/admin/devices/showWithDate/{{ $device->id }}/' + from + '/' + to,
                    type: 'GET',
                    dataType: 'json',
                    success: function (payload) {
                        const seriesData = payload[0] || [];
                        const labels = payload[1] || [];

                        renderChart(labels, seriesData);
                    },
                    error: function (xhr, b, c) {
                        console.log('xhr=' + xhr + ' b=' + b + ' c=' + c);
                    },
                    complete: function () {
                        spinner.hide();
                    }
                });
            }

            function handleNavClick(element) {
                const tabIndex = Number(element.getAttribute('tabindex'));
                document.querySelectorAll('.nav-test li a').forEach(function (anchor) {
                    anchor.className = 'nav-link py-2 px-4 nav-link';
                });
                element.className = 'nav-link py-2 px-4 active nav-link active';

                switch (tabIndex) {
                    case 0:
                        activeFrom = 1;
                        activeTo = 0;
                        customDateContainer.hide();
                        break;
                    case 1:
                        activeFrom = 7;
                        activeTo = 0;
                        customDateContainer.hide();
                        break;
                    case 2:
                        activeFrom = 30;
                        activeTo = 0;
                        customDateContainer.hide();
                        break;
                    default:
                        customDateContainer.show();
                        return;
                }

                fetchAndUpdate(activeFrom, activeTo);
                scheduleRefresh();
            }

            function initNavigation() {
                document.querySelectorAll('.nav-test li a').forEach(function (anchor) {
                    anchor.addEventListener('click', function (event) {
                        event.preventDefault();
                        handleNavClick(anchor);
                    });
                });
            }

            function initCustomDateHandlers() {
                const toInput = document.getElementById('to');
                const fromInput = document.getElementById('from');

                function computeDiffDays(dateValue) {
                    const today = new Date();
                    const target = new Date(dateValue);
                    const diffTime = Math.abs(today.setHours(0, 0, 0, 0) - target.setHours(0, 0, 0, 0));
                    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                }

                document.getElementById('custom').addEventListener('click', function (event) {
                    event.preventDefault();
                    customDateContainer.show();
                });

                toInput.addEventListener('change', function () {
                    const toDiff = computeDiffDays(toInput.value);
                    const fromDiff = computeDiffDays(fromInput.value);
                    fetchAndUpdate(fromDiff, toDiff);
                });

                window.getData = function () {
                    const toDiff = computeDiffDays(toInput.value);
                    const fromDiff = computeDiffDays(fromInput.value);
                    fetchAndUpdate(fromDiff, toDiff);
                };
            }

            renderChart(initialLabels, initialSeries, false);
            initNavigation();
            initCustomDateHandlers();
            scheduleRefresh();
        })();
    </script>
@endpush
