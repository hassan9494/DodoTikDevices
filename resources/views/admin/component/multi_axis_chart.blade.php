
<section class="box-fancy section-fullwidth text-light p-b-0">
    {{--        <div class="row">--}}
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
    {{--        </div>--}}
</section>


@push('scripts')
    <script>
        // $(document).ready(function (){
            jQuery.ajax({
                url: '/admin/devices/getMultiAxisChartData/{{$device->id}}/',
                type: 'GET',
                success: function (data) {
                    var options = {
                        chart: {
                            height: 350,
                            type: "line",
                            stacked: false
                        },
                        dataLabels: {
                            enabled: false
                        },
                        colors: ['#008ffb', '#00e396', '#feb019', '#ff4560'],
                        series: [
                            @foreach($device->deviceType->deviceParameters as $key=>$parameter)
                            {
                                name: '{{$parameter->name}} ({{$parameter->unit}})',
                                type: 'column',
                                data: data[0][{{$key}}]
                            },
                            @endforeach
                            // {
                            //     name: "Line C",
                            //     type: 'line',
                            //     data: [1.4, 2, 2.5, 10.5, 2.5, 2.8, 3.8, 40.6]
                            // },
                        ],
                        stroke: {
                            width: [4, 4, 4,4]
                        },
                        // theme: {
                        //     monochrome: {
                        //         enabled: true,
                        //         color: '#00989d',
                        //         shadeTo: 'light',
                        //         shadeIntensity: 0.65
                        //     }
                        // },
                        // fill: {
                        //     type: 'gradient',
                        //     gradient: {
                        //         shade: 'dark',
                        //         type: "horizontal",
                        //         shadeIntensity: 0.5,
                        //         gradientToColors: undefined, // optional, if not defined - uses the shades of same color in series
                        //         inverseColors: true,
                        //         opacityFrom: 1,
                        //         opacityTo: 1,
                        //         stops: [0, 50, 100],
                        //         colorStops: []
                        //     }
                        // },
                        plotOptions: {
                            bar: {
                                columnWidth: "40%"
                            }
                        },
                        xaxis: {
                            // type : 'categ',
                            categories: data[1]
                        },
                        // yaxis: [
                        //
                        //     {
                        //         opposite: true,
                        //         seriesName: 'Line C',
                        //         axisTicks: {
                        //             show: true
                        //         },
                        //         axisBorder: {
                        //             show: true,
                        //         },
                        //         title: {
                        //             text: "Line"
                        //         }
                        //     }
                        // ],
                        tooltip: {
                            shared: false,
                            intersect: true,
                            x: {
                                show: true
                            }
                        },
                        legend: {
                            horizontalAlign: "left",
                            offsetX: 40
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#multiAxisChart"), options);

                    chart.render();

                },
                error: function (xhr, b, c) {
                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                }
            });
        // })

    </script>
@endpush
