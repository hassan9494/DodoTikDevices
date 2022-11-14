<section class="box-fancy section-fullwidth text-light p-b-0">
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark"
                              style="font-size: 1rem;">{{__('message.Flow Chart')}}  </span>
                    </h3>
                </div>
                <div class="card-body pt-2" style="position: relative;">
                    <div id="chart">
                    </div>
{{--                    <div class="spinner-border  text-success" role="status" id="spinner">--}}
{{--                        <span class="sr-only">Loading...</span>--}}
{{--                    </div>--}}
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

@push('scripts')

    <script>



        var timer = {!! json_encode($devFactory->device, JSON_HEX_TAG) !!};
        // console.log(timer['time_between_two_read'])
        interval = setInterval(function() {
            jQuery.ajax({
                url: '/admin/factories/flow/{{$devFactory->id}}',
                type: 'GET',
                success: function (data) {
                    timer = 3000
                    chart.updateOptions({

                        series: [

                                @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)

                            {
                                name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                data: data[0][{{$key}}]
                            },
                            @endforeach
                        ],
                        chart: {
                            height: 500,
                            width: "100%",
                            type: 'area',
                            animations: {
                                enabled: data[1].length < 500 ? true : false,
                            }
                        },
                        labels: data[1]


                    })
                },
                error: function (xhr, b, c) {
                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                }
            });
            // your code goes here...
        }, timer['time_between_two_read'] *1000 *60);

    </script>
    <script>
        var xValues = {!! json_encode($xValues, JSON_HEX_TAG) !!};
        var yValues = {!! json_encode($paraValues, JSON_HEX_TAG) !!};
        var colors = {!! json_encode($color, JSON_HEX_TAG) !!}
        var xVals = [];
        xValues.forEach(myFunction)
        var units = [];
        function myFunction(item) {
            xVals.push(new Date(item).toLocaleString())
        }
        @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
        units.push("{{$parameter->unit}}")

        @endforeach
        var options = {
            series: [

               @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                    {
                        name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                        data: yValues[{{$key}}]
                    },
                @endforeach

            ],
            colors: colors,
            chart: {
                height: 500,
                width: "100%",
                type: 'area',
                animations: {
                    enabled: false,
                }
            },
            markers: {
                size: 0
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 4,
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '90%'
                }
            },
            xaxis: {
                type: 'datetime',
                labels: {
                    datetimeUTC: false
                },
                categories: xValues,
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y, {series, seriesIndex, dataPointIndex, w}) {
                        if (typeof y !== "undefined") {
                            return y + " " + units[seriesIndex];
                        }
                        return y;

                    }
                },
                x: {
                    format: 'M/d/y hh : mm TT',
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                fontSize: "12px",
                itemMargin: {
                    horizontal: 10,
                    vertical: 25
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush
