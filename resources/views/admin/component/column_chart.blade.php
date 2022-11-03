
<section class="box-fancy section-fullwidth text-light p-b-0">
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">


                <div class="card-body pt-2" style="position: relative;">
                    <div id="columnChart">

                    </div>
                    <div class="spinner-border  text-success" role="status" id="spinner1">
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


@push('scripts')
    <script>
            jQuery.ajax({
                url: '/admin/devices/getColumnChartData/{{$device->id}}/',
                type: 'GET',
                success: function (data) {
                    var options = {
                        chart: {
                            type: 'bar',
                            stacked: true,
                            // stackType: "100%"
                        },
                        // yaxis: {
                        //     reversed: true
                        // },
                        plotOptions: {
                            bar: {
                                borderRadius: 10,
                                distributed: true
                            }
                        },
                        series: [{
                            name: "AVG",
                            data: [
                                @if(count($testParaColumn) > 0)
                                    @foreach($testParaColumn as $key=>$parameter)

                                {
                                    x: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                    y: data[0]['paravalues'][{{$key}}]
                                },
                                @endforeach
                                @else
                                    @foreach($device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)

                                {
                                    x: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                    y: data[0]['paravalues'][{{$key}}]
                                },
                                @endforeach
                                @endif
                                ],
                        }],
                    }

                    var chart1 = new ApexCharts(document.querySelector("#columnChart"), options);
                    chart1.render();
                },
                error: function (xhr, b, c) {
                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                }
            });

    </script>
@endpush
