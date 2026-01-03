<section class="box-fancy section-fullwidth text-light p-b-0">
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">


                <div class="card-body pt-2" style="position: relative;">
                    <div id="multiAxisChart">

                    </div>
                    <div class="spinner-border  text-success" role="status" id="spinner2">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="resize-triggers">
                        <div class="expand-trigger">
                            <div style="width: 1291px; height: 399px;"></div>
                        </div>
                        <div class="contract-trigger"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@php
    $multiAxisParameters = $device->deviceType
        ? $device->deviceType->deviceParameters()->orderBy('order')->get()
        : collect();

    $multiAxisMeta = $multiAxisParameters
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
            const parameterMeta = {!! $multiAxisMeta->toJson(JSON_HEX_TAG) !!};
            const spinner = jQuery('#spinner2');
            spinner.hide();

            function buildSeries(seriesData) {
                return parameterMeta.map(function (meta, index) {
                    const name = meta.name || meta.code || 'Parameter';
                    const unit = meta.unit ? ' (' + meta.unit + ')' : '';

                    return {
                        name: name + unit,
                        type: 'column',
                        data: (seriesData[index] || []).map(function (value) {
                            return value === null ? 0 : Number(value);
                        }),
                    };
                });
            }

            spinner.show();

            jQuery.ajax({
                url: '/admin/devices/getMultiAxisChartData/{{ $device->id }}/',
                type: 'GET',
                dataType: 'json',
                success: function (payload) {
                    const seriesData = payload[0] || [];
                    const categories = payload[1] || [];

                    const options = {
                        chart: {
                            height: 350,
                            type: 'line',
                            stacked: false,
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        series: buildSeries(seriesData),
                        stroke: {
                            width: Array(parameterMeta.length).fill(4),
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '40%',
                            },
                        },
                        xaxis: {
                            categories: categories,
                        },
                        tooltip: {
                            shared: false,
                            intersect: true,
                            x: {
                                show: true,
                            },
                        },
                        legend: {
                            horizontalAlign: 'left',
                            offsetX: 40,
                        },
                    };

                    const chart = new ApexCharts(document.querySelector('#multiAxisChart'), options);
                    chart.render();
                },
                error: function (xhr, b, c) {
                    console.log('xhr=' + xhr + ' b=' + b + ' c=' + c);
                },
                complete: function () {
                    spinner.hide();
                }
            });
        })();

    </script>
@endpush
