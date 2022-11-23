<section class="box-fancy section-fullwidth text-light p-b-0">
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark" id="parameter_name"
                              style="font-size: 1rem;">{{$device_type->deviceParameters()->orderBy('order')->first()->name}}  </span>
                    </h3>

                    {{--                    <div class="card-toolbar">--}}
                    {{--                        <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test flowchart-nav" role="tablist">--}}
                    {{--                            <li class="nav-item nav-item">--}}
                    {{--                                <a href="#" role="tab" data-rb-event-key="Custom"--}}
                    {{--                                   tabindex="3" aria-selected="false"--}}
                    {{--                                   class="nav-link py-2 px-4   nav-link"--}}
                    {{--                                   id="custom">{{__('message.Custom')}}</a></li>--}}
                    {{--                            <li class="nav-item nav-item">--}}
                    {{--                                <a href="#" role="tab"--}}
                    {{--                                   data-rb-event-key="ThisMonth"--}}
                    {{--                                   tabindex="2" aria-selected="false"--}}
                    {{--                                   class="nav-link py-2 px-4   nav-link">This--}}
                    {{--                                    {{__('message.Week')}} </a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item nav-item">--}}
                    {{--                                <a href="#" role="tab"--}}
                    {{--                                   data-rb-event-key="ThisWeek"--}}
                    {{--                                   tabindex="1" aria-selected="false"--}}
                    {{--                                   class="nav-link py-2 px-4   nav-link">--}}
                    {{--                                    {{__('message.Last 24 hour')}} </a>--}}
                    {{--                            </li>--}}
                    {{--                            <li class="nav-item nav-item">--}}
                    {{--                                <a href="#" role="tab"--}}
                    {{--                                   data-rb-event-key="ThisDay"--}}
                    {{--                                   aria-selected="true"--}}
                    {{--                                   tabindex="0" id="thisDay"--}}
                    {{--                                   class="nav-link py-2 px-4  nav-link active">{{__('message.This Day')}}</a></li>--}}
                    {{--                        </ul>--}}
                    {{--                    </div>--}}

                </div>

                {{--                <div class="custom-date row" id="custom-date" style="display:none">--}}

                {{--                    <div class="col-md-2"></div>--}}
                {{--                    <div class="col-md-8">--}}
                {{--                        <div class="row">--}}
                {{--                            <div class="col-md-5">--}}
                {{--                                <label for="from" class="form-label" style="color: #00989d">{{__('message.From')}}</label>--}}
                {{--                                <input id="from" min="{{\Carbon\Carbon::now()->subMonth()->format("Y-m-d")}}"--}}
                {{--                                       max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" type="date" name="from"--}}
                {{--                                       class="form-control"--}}
                {{--                                       value="{{\Carbon\Carbon::now()->format("Y-m-01")}}">--}}
                {{--                            </div>--}}
                {{--                            <div class="col-md-5">--}}
                {{--                                <label for="to" class="form-label" style="color: #00989d">{{__('message.To')}}</label>--}}
                {{--                                <input id="to" min="{{\Carbon\Carbon::now()->subMonth()->format("Y-m-d")}}"--}}
                {{--                                       max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" type="date" name="to"--}}
                {{--                                       class="form-control"--}}
                {{--                                       value="{{\Carbon\Carbon::now()->format("Y-m-d")}}">--}}
                {{--                            </div>--}}
                {{--                            <div class="col-md-2">--}}
                {{--                                <label for="to" class="form-label" style="color: #ffffff">get data</label>--}}
                {{--                                <button class="btn btn-primary" onclick="getData()" style="color: #b5b5c3;background: #00989d;border-color: #00989d;">get data</button>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}


                {{--                    </div>--}}
                {{--                    <div class="col-md-2">--}}
                {{--                                                        <button class="btn btn-primary" onclick="test()">get data</button>--}}
                {{--                    </div>--}}

                {{--                </div>--}}

                <div class="card-body pt-2" style="position: relative;">
                    <div class="row">
                        <div class="col-lg-9 col-xxl-9 order-1 order-xxl-1 mb-4">
                            <div id="chart1">
                            </div>
                            <div class="spinner-border  text-success" role="status" id="spinnerLine"
                                 style="position: absolute;left: 45%">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <div class="resize-triggers">
                                <div class="expand-trigger">
                                    <div style="width: 1291px; height: 399px;"></div>
                                </div>
                                <div class="contract-trigger"></div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-xxl-3 order-1 order-xxl-1 mb-4">
                            <ul class="nav1 nav-pills nav-pills-sm nav-dark-75 nav nav-test flowchart-nav"
                                role="tablist" style="justify-content: center">
                                @foreach($device_type->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                                    <button title="Export" id="{{$parameter->id}}"
                                       onclick="getParametervalue(this.id)"
                                       class="btn btn-edit btn-group-lg m-1"
                                       style="font-size: 0.9rem;width:45%;float: right;background-color: {{$device_type->deviceParameters()->where('code', $parameter->code)->first()->pivot->color}}!important;"> {{$parameter->name}} </button>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        function getParametervalue(para) {
            jQuery.ajax({
                url: '/admin/devices/showParameterData/{{$devFactory->id}}/' + para,
                type: 'GET',
                success: function (resp) {
                    console.log()
                     xValues = resp[1];
                     yValues = resp[0];
                     colorss = resp[3];
                     units1 = [];
                    @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                        {{--units1.push("{{$parameter->unit}}")--}}
                        units1["{{$parameter->code}}"] = "{{$parameter->unit}}"

                    @endforeach
                    const generateColors2 = (data,code) => {
                        return data.map((d, idx) => {
                            var color = resp[5]
                            if (colorss.length > 0) {
                                color = colorss[idx]
                            }
                            return {
                                offset: idx / data.length * 100,
                                color,
                                opacity: 1,
                                data: d
                            }
                        })
                    }
                    chart1.updateOptions({


                        tooltip: {
                            custom: function ({series, seriesIndex, dataPointIndex, w}) {
                                var data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];

                                var liColor = "color: " + resp[5] + ""
                                if (colorss.length > 0){
                                    liColor = "color: " + colorss[dataPointIndex] + ""
                                }


                                return '<ul style="margin-right: 30px;margin-top: 15px;list-style-type: none">' +
                                    '<li class="tooltip-custom" style="' + liColor + '"><b>'+resp[4]['code'] +'</b>  :   ' + data + ' ' + resp[4]['unit'] + '</li>' +
                                    '</ul>';
                            },
                            shared: true,
                            followCursor: false,
                            intersect: false,
                            theme: 'light',
                            fillSeriesColor: false,
                            style: {
                                fontSize: '12px',
                                color: '#f00000',
                                backgroundColor: '#f00000',
                                fontFamily: undefined
                            },
                            y: {
                                formatter: function (y, {series, seriesIndex, dataPointIndex, w}) {
                                    if (typeof y !== "undefined") {
                                        return y + " " + units1[seriesIndex];
                                    }
                                    return y;

                                }
                            },
                            x: {
                                format: 'M/d/y hh : mm TT',
                            }
                        },

                        series: [
                            {
                                name: "PM2.5",
                                data: yValues[0]
                            }
                        ],
                        // colors:['#00f0ff'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shade: 'light',
                                inverseColors: true,
                                shadeIntensity: 0.25,
                                type: 'horizontal',
                                opacityFrom: 0.4,
                                opacityTo: 0.9,
                                colorStops: generateColors2(yValues[0],resp[4]['code'])
                            },
                        },
                        labels: xValues


                    })
                    document.getElementById('parameter_name').innerHTML = resp[4]['name'];

                },
                error: function (xhr, b, c) {
                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                }
            });

            var $loading = $('#spinnerLine').show();
            $(document)
                .ajaxStart(function () {
                    $loading.show();
                })
                .ajaxStop(function () {
                    $loading.hide();
                });
        }
    </script>
    <script>
        var xValues = {!! json_encode($xValues, JSON_HEX_TAG) !!};
        var yValues = {!! json_encode($paraValues[0], JSON_HEX_TAG) !!};
        var colorss = {!! json_encode($multiColor, JSON_HEX_TAG) !!};
        var units1 = [];
        @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
            {{--units1.push("{{$parameter->unit}}")--}}
            units1["{{$parameter->code}}"] = "{{$parameter->unit}}"

        @endforeach
        const generateColors = (data) => {
            return data.map((d, idx) => {
                var color = "{{$device_type->deviceParameters()->orderBy('order')->first()->pivot->color}}"
                if (colorss.length > 0) {
                     color = colorss[idx]
                }
                return {
                    offset: idx / data.length * 100,
                    color,
                    opacity: 1,
                    data: d
                }
            })
        }
        let options1 = {
            chart: {
                height: 350,
                width: "100%",
                type: "area",
                animations: {
                    enabled: false,
                }
            },

            markers: {
                hover: {
                    size: undefined,
                    sizeOffset: 1
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: false,
                width: 0,
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
                custom: function ({series, seriesIndex, dataPointIndex, w}) {
                    var data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];
                    var liColor = "color: " + "{{$device_type->deviceParameters()->orderBy('order')->first()->pivot->color}}"+ ""
                    if (colorss.length > 0){
                        liColor = "color: " + colorss[dataPointIndex] + ""
                    }
                    // var liColor = "color: " + colorss[dataPointIndex] + ""

                    return '<ul style="margin-right: 30px;margin-top: 15px;list-style-type: none">' +
                        '<li class="tooltip-custom" style="' + liColor + '"><b>{{$device_type->deviceParameters()->orderBy('order')->first()->code}} </b>  :   ' + data + ' ' + "{{$device_type->deviceParameters()->orderBy('order')->first()->unit}}" + '</li>' +
                        '</ul>';
                },
                shared: true,
                followCursor: false,
                intersect: false,
                theme: 'light',
                fillSeriesColor: false,
                style: {
                    fontSize: '12px',
                    color: '#f00000',
                    backgroundColor: '#f00000',
                    fontFamily: undefined
                },
                y: {
                    formatter: function (y, {series, seriesIndex, dataPointIndex, w}) {
                        if (typeof y !== "undefined") {
                            return y + " " + units1[seriesIndex];
                        }
                        return y;

                    }
                },
                x: {
                    format: 'M/d/y hh : mm TT',
                }
            },

            series: [
                {
                    name: "PM2.5",
                    data: yValues
                }
            ],
            // colors:['#00f0ff'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    inverseColors: true,
                    shadeIntensity: 0.25,
                    type: 'horizontal',
                    opacityFrom: 0.4,
                    opacityTo: 0.9,
                    colorStops: generateColors(yValues)
                },
            },


            legend: {
                position: "top",
                horizontalAlign: "left",
                offsetX: -15,
                fontWeight: "bold",
            }
        };
        let chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        chart1.render();
    </script>


    {{--    <script>--}}
    {{--        var timer = {!! json_encode($devFactory->device, JSON_HEX_TAG) !!};--}}
    {{--        // console.log(timer['time_between_two_read'])--}}
    {{--        interval = setInterval(function() {--}}
    {{--            jQuery.ajax({--}}
    {{--                url: '/admin/factories/flow/{{$devFactory->id}}/1/0',--}}
    {{--                type: 'GET',--}}
    {{--                success: function (data) {--}}
    {{--                    timer = 3000--}}
    {{--                    chart.updateOptions({--}}

    {{--                        series: [--}}

    {{--                                @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)--}}

    {{--                            {--}}
    {{--                                name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",--}}
    {{--                                data: data[0][{{$key}}]--}}
    {{--                            },--}}
    {{--                            @endforeach--}}
    {{--                        ],--}}
    {{--                        chart: {--}}
    {{--                            height: 500,--}}
    {{--                            width: "100%",--}}
    {{--                            type: 'area',--}}
    {{--                            animations: {--}}
    {{--                                enabled: data[1].length < 500 ? true : false,--}}
    {{--                            }--}}
    {{--                        },--}}
    {{--                        labels: data[1]--}}


    {{--                    })--}}
    {{--                },--}}
    {{--                error: function (xhr, b, c) {--}}
    {{--                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);--}}
    {{--                }--}}
    {{--            });--}}
    {{--            // your code goes here...--}}
    {{--        }, timer['time_between_two_read'] *1000 *60);--}}

    {{--    </script>--}}

    {{--    <script>--}}
    {{--        var el = document.querySelectorAll('.nav-test li a');--}}
    {{--        var fromm = -1;--}}
    {{--        var too = -1;--}}
    {{--        for (let i = 0; i < el.length; i++) {--}}
    {{--            el[i].onclick = function () {--}}
    {{--                var c = 0;--}}
    {{--                while (c < el.length) {--}}
    {{--                    el[c++].className = 'nav-link py-2 px-4 nav-link';--}}
    {{--                }--}}
    {{--                el[i].className = 'nav-link py-2 px-4  active nav-link active';--}}

    {{--                if (el[i].getAttribute('tabindex') == 0) {--}}
    {{--                    fromm = 1;--}}
    {{--                    too = 0;--}}
    {{--                    if (interval){--}}

    {{--                    }else {--}}
    {{--                        interval = setInterval(function() {--}}
    {{--                            console.log('1111111')--}}
    {{--                            jQuery.ajax({--}}
    {{--                                url: '/admin/factories/flow/{{$devFactory->id}}/1/0',--}}
    {{--                                type: 'GET',--}}
    {{--                                success: function (data) {--}}
    {{--                                    timer = 3000--}}
    {{--                                    chart.updateOptions({--}}

    {{--                                        series: [--}}

    {{--                                                @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)--}}

    {{--                                            {--}}
    {{--                                                name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",--}}
    {{--                                                data: data[0][{{$key}}]--}}
    {{--                                            },--}}
    {{--                                            @endforeach--}}
    {{--                                        ],--}}
    {{--                                        chart: {--}}
    {{--                                            height: 500,--}}
    {{--                                            width: "100%",--}}
    {{--                                            type: 'area',--}}
    {{--                                            animations: {--}}
    {{--                                                enabled: data[1].length < 500 ? true : false,--}}
    {{--                                            }--}}
    {{--                                        },--}}
    {{--                                        labels: data[1]--}}


    {{--                                    })--}}
    {{--                                },--}}
    {{--                                error: function (xhr, b, c) {--}}
    {{--                                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);--}}
    {{--                                }--}}
    {{--                            });--}}
    {{--                            // your code goes here...--}}
    {{--                        }, timer['time_between_two_read'] *1000 * 60);--}}
    {{--                    }--}}

    {{--                } else if (el[i].getAttribute('tabindex') == 1) {--}}
    {{--                    fromm = 2;--}}
    {{--                    too = 0;--}}
    {{--                    clearInterval(interval);--}}
    {{--                    interval = false;--}}
    {{--                } else if (el[i].getAttribute('tabindex') == 2) {--}}
    {{--                    fromm = 7;--}}
    {{--                    too = 0;--}}
    {{--                    clearInterval(interval);--}}
    {{--                    interval = false;--}}
    {{--                }else {--}}
    {{--                    clearInterval(interval);--}}
    {{--                    interval = false;--}}
    {{--                }--}}

    {{--            };--}}
    {{--        }--}}
    {{--        document.addEventListener('DOMContentLoaded', function () {--}}
    {{--            $('.nav-link').click(function (e) {--}}

    {{--                $('#custom-date').attr('style', 'display :none')--}}

    {{--                jQuery.ajax({--}}
    {{--                    url: '/admin/factories/flow/{{$devFactory->id}}/' + fromm + '/' + too,--}}
    {{--                    type: 'GET',--}}
    {{--                    success: function (data) {--}}
    {{--                        chart.updateOptions({--}}

    {{--                            series: [--}}

    {{--                                    @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)--}}

    {{--                                {--}}
    {{--                                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",--}}
    {{--                                    data: data[0][{{$key}}]--}}
    {{--                                },--}}
    {{--                                @endforeach--}}
    {{--                            ],--}}
    {{--                            chart: {--}}
    {{--                                height: 500,--}}
    {{--                                width: "100%",--}}
    {{--                                type: 'area',--}}
    {{--                                animations: {--}}
    {{--                                    enabled: data[1].length < 500 ? true : false,--}}
    {{--                                }--}}
    {{--                            },--}}
    {{--                            labels: data[1]--}}


    {{--                        })--}}
    {{--                    },--}}
    {{--                    error: function (xhr, b, c) {--}}
    {{--                        console.log("xhr=" + xhr + " b=" + b + " c=" + c);--}}
    {{--                    }--}}
    {{--                });--}}
    {{--                var $loading = $('#spinner').show();--}}
    {{--                $(document)--}}
    {{--                    .ajaxStart(function () {--}}
    {{--                        $loading.show();--}}
    {{--                    })--}}
    {{--                    .ajaxStop(function () {--}}
    {{--                        $loading.hide();--}}
    {{--                    });--}}

    {{--            })--}}
    {{--        });--}}
    {{--        document.addEventListener('DOMContentLoaded', function () {--}}
    {{--            $('#custom').click(function (e) {--}}

    {{--                $('#custom-date').attr('style', 'display :block')--}}
    {{--            })--}}
    {{--        });--}}
    {{--        document.addEventListener('DOMContentLoaded', function () {--}}
    {{--            $('#to').change(function (e) {--}}


    {{--                var from = document.getElementById('from')--}}
    {{--                var today = new Date();--}}
    {{--                var dd = String(today.getDate()).padStart(2, '0');--}}
    {{--                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!--}}
    {{--                var yyyy = today.getFullYear();--}}

    {{--                today = yyyy + '-' + mm + '-' + dd;--}}
    {{--                const diffTime = Math.abs(Date.parse(today) - Date.parse($(this).val()));--}}
    {{--                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));--}}
    {{--                const diffTime1 = Math.abs(Date.parse(today) - Date.parse(from.value));--}}
    {{--                const diffDays1 = Math.ceil(diffTime1 / (1000 * 60 * 60 * 24));--}}

    {{--                jQuery.ajax({--}}
    {{--                    url: '/admin/factories/flow/{{$devFactory->id}}/' + diffDays1 + '/' + diffDays,--}}
    {{--                    type: 'GET',--}}
    {{--                    success: function (data) {--}}
    {{--                        chart.updateOptions({--}}

    {{--                            series: [--}}

    {{--                                    @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)--}}

    {{--                                {--}}
    {{--                                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",--}}
    {{--                                    data: data[0][{{$key}}]--}}
    {{--                                },--}}
    {{--                                @endforeach--}}
    {{--                            ],--}}
    {{--                            chart: {--}}
    {{--                                height: 500,--}}
    {{--                                width: "100%",--}}
    {{--                                type: 'area',--}}
    {{--                                animations: {--}}
    {{--                                    enabled: data[1].length < 500 ? true : false,--}}
    {{--                                }--}}
    {{--                            },--}}
    {{--                            labels: data[1]--}}


    {{--                        })--}}
    {{--                    },--}}
    {{--                    error: function (xhr, b, c) {--}}
    {{--                        console.log("xhr=" + xhr + " b=" + b + " c=" + c);--}}
    {{--                    }--}}
    {{--                });--}}
    {{--            })--}}
    {{--        });--}}

    {{--        function getData(){--}}
    {{--            var from = document.getElementById('from')--}}
    {{--            var today = new Date();--}}
    {{--            var dd = String(today.getDate()).padStart(2, '0');--}}
    {{--            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!--}}
    {{--            var yyyy = today.getFullYear();--}}

    {{--            today = yyyy + '-' + mm + '-' + dd;--}}
    {{--            const diffTime = Math.abs(Date.parse(today) - Date.parse($('#to').val()));--}}
    {{--            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));--}}
    {{--            const diffTime1 = Math.abs(Date.parse(today) - Date.parse(from.value));--}}
    {{--            const diffDays1 = Math.ceil(diffTime1 / (1000 * 60 * 60 * 24));--}}
    {{--console.log(diffDays)--}}
    {{--            console.log(diffDays1)--}}
    {{--            jQuery.ajax({--}}
    {{--                url: '/admin/factories/flow/{{$devFactory->id}}/' + diffDays1 + '/' + diffDays,--}}
    {{--                type: 'GET',--}}
    {{--                success: function (data) {--}}
    {{--                    chart.updateOptions({--}}

    {{--                        series: [--}}

    {{--                                @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)--}}

    {{--                            {--}}
    {{--                                name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",--}}
    {{--                                data: data[0][{{$key}}]--}}
    {{--                            },--}}
    {{--                            @endforeach--}}
    {{--                        ],--}}
    {{--                        chart: {--}}
    {{--                            height: 500,--}}
    {{--                            width: "100%",--}}
    {{--                            type: 'area',--}}
    {{--                            animations: {--}}
    {{--                                enabled: data[1].length < 500 ? true : false,--}}
    {{--                            }--}}
    {{--                        },--}}
    {{--                        labels: data[1]--}}


    {{--                    })--}}
    {{--                },--}}
    {{--                error: function (xhr, b, c) {--}}
    {{--                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);--}}
    {{--                }--}}
    {{--            });--}}
    {{--        }--}}
    {{--    </script>--}}

@endpush
