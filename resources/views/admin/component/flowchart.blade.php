

    <section class="box-fancy section-fullwidth text-light p-b-0">
            <div class="row" style="text-align: -webkit-center;">
                <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                    <div class="card card-custom mb-4">
                        <div class="card-header border-0 pt-5">
                            <h3 class="card-title align-items-start flex-column">
                                <span
                                    class="card-label font-weight-bolder text-dark" style="font-size: 1rem;">{{__('message.device_id')}} : {{$device->device_id}} </span>
                                <span id="status"
                                      class="card-label font-weight-bolder text-dark"
                                      style="margin-top: 15px;font-size: 1rem;">{{__('message.status')}} : {{$status}}  <i
                                        class="fas {{$status == "Offline" ? 'fa-times' : 'fa-check'  }}"
                                        style="color:{{$status == "Offline" ? 'red' : 'green'  }} "></i> </span>
                            </h3>

                            <div class="card-toolbar">
                                <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">
                                    <li class="nav-item nav-item">
                                        <a href="#" role="tab" data-rb-event-key="Custom"
                                           tabindex="3" aria-selected="false"
                                           class="nav-link py-2 px-4   nav-link {{$label == 2 ? "active" : ""}}"
                                           id="custom">{{__('message.Custom')}}</a></li>
                                    <li class="nav-item nav-item">
                                        <a href="#" role="tab"
                                           data-rb-event-key="ThisMonth"
                                           tabindex="2" aria-selected="false"
                                           class="nav-link py-2 px-4   nav-link {{$label == 30 ? "active" : ""}}">This
                                            {{__('message.Month')}} </a>
                                    </li>
                                    <li class="nav-item nav-item">
                                        <a href="#" role="tab"
                                           data-rb-event-key="ThisWeek"
                                           tabindex="1" aria-selected="false"
                                           class="nav-link py-2 px-4   nav-link {{$label == 7 ? "active" : ""}}">This
                                            {{__('message.Week')}} </a>
                                    </li>
                                    <li class="nav-item nav-item">
                                        <a href="#" role="tab"
                                           data-rb-event-key="ThisDay"
                                           aria-selected="true"
                                           tabindex="0"
                                           class="nav-link py-2 px-4  nav-link {{$label == 1 ? "active" : ""}} ">{{__('message.This Day')}}</a></li>
                                </ul>
                            </div>

                        </div>
                        <div class="custom-date" id="custom-date" style="display: {{$label == 2 ? "block" : "none"}}">

                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="from" class="form-label">{{__('message.From')}}</label>
                                        <input id="from" min="{{\Carbon\Carbon::now()->subMonth()->format("Y-m-d")}}"
                                               max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" type="date" name="from"
                                               class="form-control"
                                               value="{{\Carbon\Carbon::now()->format("Y-m-01")}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="to" class="form-label">{{__('message.To')}}</label>
                                        <input id="to" min="{{\Carbon\Carbon::now()->subMonth()->format("Y-m-d")}}"
                                               max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" type="date" name="to"
                                               class="form-control"
                                               value="{{\Carbon\Carbon::now()->format("Y-m-d")}}">
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-2"></div>

                        </div>

                        <div class="card-body pt-2" style="position: relative;">
                            <div id="chart">

                            </div>
                            <div class="spinner-border  text-success" role="status" id="spinner">
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
        var el = document.querySelectorAll('.nav-test li a');
        var fromm = -1;
        var too = -1;
        for (let i = 0; i < el.length; i++) {
            el[i].onclick = function () {
                var c = 0;
                while (c < el.length) {
                    el[c++].className = 'nav-link py-2 px-4 nav-link';
                }
                el[i].className = 'nav-link py-2 px-4  active nav-link active';

                if (el[i].getAttribute('tabindex') == 0) {
                    fromm = 1;
                    too = 0;
                } else if (el[i].getAttribute('tabindex') == 1) {
                    fromm = 7;
                    too = 0;
                } else if (el[i].getAttribute('tabindex') == 2) {
                    fromm = 30;
                    too = 0;
                }

            };
        }
        document.addEventListener('DOMContentLoaded', function () {
            $('.nav-link').click(function (e) {

                $('#custom-date').attr('style', 'display :none')

                jQuery.ajax({
                    url: '/admin/devices/showWithDate/{{$device->id}}/' + fromm + '/' + too,
                    type: 'GET',
                    success: function (data) {
                        chart.updateOptions({

                            series: [
                                @if(count($testPara) > 0 )
                                    @foreach($testPara as $key=>$parameter)

                                {
                                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                    data: data[0][{{$key}}]
                                },
                                @endforeach
                                @else
                                    @foreach($device->deviceType->deviceParameters as $key=>$parameter)

                                {
                                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                    data: data[0][{{$key}}]
                                },
                                @endforeach
                                @endif
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
                var $loading = $('#spinner').show();
                $(document)
                    .ajaxStart(function () {
                        $loading.show();
                    })
                    .ajaxStop(function () {
                        $loading.hide();
                    });

            })
        });
        document.addEventListener('DOMContentLoaded', function () {
            $('#custom').click(function (e) {

                $('#custom-date').attr('style', 'display :block')
            })
        });
        document.addEventListener('DOMContentLoaded', function () {
            $('#to').change(function (e) {


                var from = document.getElementById('from')
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();

                today = yyyy + '-' + mm + '-' + dd;
                const diffTime = Math.abs(Date.parse(today) - Date.parse($(this).val()));
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const diffTime1 = Math.abs(Date.parse(today) - Date.parse(from.value));
                const diffDays1 = Math.ceil(diffTime1 / (1000 * 60 * 60 * 24));
                jQuery.ajax({
                    url: '/admin/devices/showWithDate/{{$device->id}}/' + diffDays1 + '/' + diffDays,
                    type: 'GET',
                    success: function (data) {
                        chart.updateOptions({
                            series: [
                                @if(count($testPara) > 0)
                                    @foreach($testPara as $key=>$parameter)

                                {
                                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                    data: data[0][{{$key}}]
                                },
                                @endforeach
                                @else
                                    @foreach($device->deviceType->deviceParameters as $key=>$parameter)

                                {
                                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                    data: data[0][{{$key}}]
                                },
                                @endforeach
                                @endif
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
            })
        });
    </script>
    <script>


        var xValues = {!! json_encode($xValues, JSON_HEX_TAG) !!};
        var yValues = {!! json_encode($paraValues, JSON_HEX_TAG) !!};
        var xVals = [];
        xValues.forEach(myFunction)
        var units = [];

        function myFunction(item) {
            xVals.push(new Date(item).toLocaleString())
        }


        @foreach($device->deviceType->deviceParameters as $key=>$parameter)
        units.push("{{$parameter->unit}}")

        @endforeach
        var labels = {{$label}}
        var options = {
            series: [
                @if(count($testPara) > 0)
                    @foreach($testPara as $key=>$parameter)

                {
                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                    data: yValues[{{$key}}]
                },
                @endforeach
                @else
                    @foreach($device->deviceType->deviceParameters as $key=>$parameter)

                {
                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                    data: yValues[{{$key}}]
                },
                @endforeach
                @endif

            ],
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
                    horizontal: 25,
                    vertical: 0
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush
