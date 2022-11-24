<section class="box-fancy section-fullwidth text-light p-b-0">
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">
                <div class="card-header border-0 pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark" id="parameter_name"
                              style="font-size: 1rem;">{{$firstParameter->name}}  </span>
                    </h3>
                    <div class="card-toolbar">
                        <button parameter="{{$firstParameter->id}}" title="lineAllData" id="lineAllData"
                                onclick="getParametervaluewithDate(this.id)"
                                class="btn btn-edit btn-lg py-2 px-4 m-2"
                                style="background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;">
                            {{__('message.All Data')}}
                        </button>
                        <button parameter="{{$firstParameter->id}}" title="lineCustom" id="lineCustom"
                                onclick="getParametervaluewithCustomDate(this.id)"
                                class="btn btn-edit btn-lg py-2 px-4 m-2"
                                style="background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;">
                            {{__('message.Custom')}}
                        </button>
                        <button parameter="{{$firstParameter->id}}" title="lineWeek" id="lineWeek"
                                onclick="getParametervaluewithDate(this.id)"
                                class="btn btn-edit btn-lg py-2 px-4 m-2"
                                style="background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;">
                            This
                            {{__('message.Week')}}
                        </button>
                        <button parameter="{{$firstParameter->id}}" title="line24hour" id="line24hour"
                                onclick="getParametervaluewithDate(this.id)"
                                class="btn btn-edit btn-lg py-2 px-4 m-2"
                                style="background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;">
                            {{__('message.Last 24 hour')}}

                        </button>
                        <button parameter="{{$firstParameter->id}}" title="lineToday" id="lineToday"
                                onclick="getParametervaluewithDate(this.id)"
                                class="btn btn-edit btn-lg py-2 px-4 m-2"
                                style="background-color:#00989d!important;color: #b5b5c3!important;font-size: 1rem;">
                            {{__('message.This Day')}}
                        </button>
                    </div>
                </div>
                <div class="custom-date row" id="lineCustom-date" style="display:none">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-5">
                                <label for="from" class="form-label" style="color: #00989d">{{__('message.From')}}</label>
                                <input id="lineFrom" min="{{\Carbon\Carbon::now()->subMonth()->format("Y-m-d")}}"
                                       max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" type="date" name="from"
                                       class="form-control"
                                       value="{{\Carbon\Carbon::now()->format("Y-m-01")}}">
                            </div>
                            <div class="col-md-5">
                                <label for="to" class="form-label" style="color: #00989d">{{__('message.To')}}</label>
                                <input id="lineTo" min="{{\Carbon\Carbon::now()->subMonth()->format("Y-m-d")}}"
                                       max="{{\Carbon\Carbon::now()->format("Y-m-d")}}" type="date" name="to"
                                       class="form-control"
                                       value="{{\Carbon\Carbon::now()->format("Y-m-d")}}">
                            </div>
                            <div class="col-md-2">
                                <label for="to" class="form-label" style="color: #ffffff">get data</label>
                                <button class="btn btn-primary" onclick="getLineData()" style="color: #b5b5c3;background: #00989d;border-color: #00989d;">get data</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
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
                                    <button title="{{$parameter->name}}" id="{{$parameter->id}}"
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
        function getParametervaluewithCustomDate(id){

            calcFromAndTo(id);
            changeButtonColor(id);
        }
        document.addEventListener('DOMContentLoaded', function () {
            $('#lineCustom').click(function (e) {

                $('#lineCustom-date').attr('style', 'display :block')
            })
        });
        document.addEventListener('DOMContentLoaded', function () {
            $('#lineTo').change(function (e) {

                var $loadingLine = $('#spinnerLine').show();
                var para1 = $('#lineCustom').attr('parameter')
                $loadingLine.show();
                console.log(fromLine+' test : '+toLine)
                var from = document.getElementById('lineFrom')
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
                    url: '/admin/devices/showParameterDataWithDate/{{$devFactory->id}}/' + para1 +'/' + diffDays1 + '/' + diffDays,
                    type: 'GET',
                    success: function (resp) {
                        xValues = resp[1];
                        yValues = resp[0];
                        colorss = resp[3];
                        units1 = [];
                        @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                            units1["{{$parameter->code}}"] = "{{$parameter->unit}}"
                        @endforeach
                        const generateColors2 = (data, code) => {
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

                            chart: {
                                height: 350,
                                width: "100%",
                                type: "area",
                                animations: {
                                    enabled: true,
                                }
                            },
                            tooltip: {
                                custom: function ({series, seriesIndex, dataPointIndex, w}) {
                                    var data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];

                                    var liColor = "color: " + resp[5] + ""
                                    if (colorss.length > 0) {
                                        liColor = "color: " + colorss[dataPointIndex] + ""
                                    }


                                    return '<ul style="margin-right: 30px;margin-top: 15px;list-style-type: none">' +
                                        '<li class="tooltip-custom" style="' + liColor + '"><b>' + resp[4]['code'] + '</b>  :   ' + data + ' ' + resp[4]['unit'] + '</li>' +
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
                                    colorStops: generateColors2(yValues[0], resp[4]['code'])
                                },
                            },
                            labels: xValues


                        })
                        document.getElementById('parameter_name').innerHTML = resp[4]['name'];
                        $loadingLine.hide();
                    },
                    error: function (xhr, b, c) {
                        console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                        $loadingLine.hide();
                    }
                });
            })
        });

        function getLineData(){
            var $loadingLine = $('#spinnerLine').show();
            var para1 = $('#lineCustom').attr('parameter')
            $loadingLine.show();
            console.log(fromLine+' test : '+toLine)
            var from = document.getElementById('lineFrom')
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            const diffTime = Math.abs(Date.parse(today) - Date.parse($('#lineTo').val()));
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const diffTime1 = Math.abs(Date.parse(today) - Date.parse(from.value));
            const diffDays1 = Math.ceil(diffTime1 / (1000 * 60 * 60 * 24));
            console.log(diffDays)
            console.log(diffDays1)
            jQuery.ajax({
                url: '/admin/devices/showParameterDataWithDate/{{$devFactory->id}}/' + para1 +'/' + diffDays1 + '/' + diffDays,
                type: 'GET',
                success: function (resp) {
                    xValues = resp[1];
                    yValues = resp[0];
                    colorss = resp[3];
                    units1 = [];
                    @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                        units1["{{$parameter->code}}"] = "{{$parameter->unit}}"
                    @endforeach
                    const generateColors2 = (data, code) => {
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

                        chart: {
                            height: 350,
                            width: "100%",
                            type: "area",
                            animations: {
                                enabled: true,
                            }
                        },
                        tooltip: {
                            custom: function ({series, seriesIndex, dataPointIndex, w}) {
                                var data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];

                                var liColor = "color: " + resp[5] + ""
                                if (colorss.length > 0) {
                                    liColor = "color: " + colorss[dataPointIndex] + ""
                                }


                                return '<ul style="margin-right: 30px;margin-top: 15px;list-style-type: none">' +
                                    '<li class="tooltip-custom" style="' + liColor + '"><b>' + resp[4]['code'] + '</b>  :   ' + data + ' ' + resp[4]['unit'] + '</li>' +
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
                                colorStops: generateColors2(yValues[0], resp[4]['code'])
                            },
                        },
                        labels: xValues


                    })
                    document.getElementById('parameter_name').innerHTML = resp[4]['name'];
                    $loadingLine.hide();
                },
                error: function (xhr, b, c) {
                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                    $loadingLine.hide();
                }
            });
        }
    </script>
    <script>
        var fromLine = 1;
        var toLine = 0;
        function calcFromAndTo(id){
            // console.log(id)
            if (id == 'lineToday'){
                console.log(id)
                fromLine = 1;
                toLine = 0;
            }else if (id == 'line24hour'){
                console.log(id)
                fromLine = 2;
                toLine = 0;
            }else if (id == 'lineWeek'){
                fromLine = 7;
                toLine = 0;
            }else if (id == 'lineAllData'){
                fromLine = 0;
                toLine = 0;
            }else {

            }
        }
        function getParametervaluewithDate(id){
            $('#lineCustom-date').attr('style', 'display :none')
            calcFromAndTo(id);
            changeButtonColor(id);
            var $loadingLine = $('#spinnerLine').show();
            var para1 = $('#'+id+'').attr('parameter')
            $loadingLine.show();
            console.log(fromLine+' test : '+toLine)
            jQuery.ajax({
                url: '/admin/devices/showParameterDataWithDate/{{$devFactory->id}}/' + para1 +'/' + fromLine + '/' + toLine,
                type: 'GET',
                success: function (resp) {
                    xValues = resp[1];
                    yValues = resp[0];
                    colorss = resp[3];
                    units1 = [];
                    @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                        units1["{{$parameter->code}}"] = "{{$parameter->unit}}"
                    @endforeach
                    const generateColors2 = (data, code) => {
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

                        chart: {
                            height: 350,
                            width: "100%",
                            type: "area",
                            animations: {
                                enabled: true,
                            }
                        },
                        tooltip: {
                            custom: function ({series, seriesIndex, dataPointIndex, w}) {
                                var data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];

                                var liColor = "color: " + resp[5] + ""
                                if (colorss.length > 0) {
                                    liColor = "color: " + colorss[dataPointIndex] + ""
                                }


                                return '<ul style="margin-right: 30px;margin-top: 15px;list-style-type: none">' +
                                    '<li class="tooltip-custom" style="' + liColor + '"><b>' + resp[4]['code'] + '</b>  :   ' + data + ' ' + resp[4]['unit'] + '</li>' +
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
                                colorStops: generateColors2(yValues[0], resp[4]['code'])
                            },
                        },
                        labels: xValues


                    })
                    document.getElementById('parameter_name').innerHTML = resp[4]['name'];
                    $loadingLine.hide();
                },
                error: function (xhr, b, c) {
                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                    $loadingLine.hide();
                }
            });
         }
        function changeButtonColor(id){
            $('#lineAllData').attr('style','background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;')
            $('#lineCustom').attr('style','background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;')
            $('#lineWeek').attr('style','background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;')
            $('#line24hour').attr('style','background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;')
            $('#lineToday').attr('style','background-color:transparent!important;color: #b5b5c3!important;font-size: 1rem;')
            $('#'+id+'').attr('style','background-color:#00989d!important;color: #b5b5c3!important;font-size: 1rem;')
        }

        function changeButtonParameter(id){
            $('#lineAllData').attr('parameter',id)
            $('#lineCustom').attr('parameter',id)
            $('#lineWeek').attr('parameter',id)
            $('#line24hour').attr('parameter',id)
            $('#lineToday').attr('parameter',id)
            $('#'+id+'').attr('parameter',id)
        }
    </script>
    <script>
        function getParametervalue(para) {
            changeButtonColor('lineToday');
            $('#lineCustom-date').attr('style', 'display :none')
            var $loadingLine = $('#spinnerLine').show();
            $loadingLine.show();
            jQuery.ajax({
                url: '/admin/devices/showParameterData/{{$devFactory->id}}/' + para,
                type: 'GET',
                success: function (resp) {
                    changeButtonParameter(resp[4]['id']);
                    xValues = resp[1];
                    yValues = resp[0];
                    colorss = resp[3];
                    units1 = [];
                    @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                        units1["{{$parameter->code}}"] = "{{$parameter->unit}}"
                    @endforeach
                    const generateColors2 = (data, code) => {
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

                        chart: {
                            height: 350,
                            width: "100%",
                            type: "area",
                            animations: {
                                enabled: true,
                            }
                        },
                        tooltip: {
                            custom: function ({series, seriesIndex, dataPointIndex, w}) {
                                var data = w.globals.initialSeries[seriesIndex].data[dataPointIndex];

                                var liColor = "color: " + resp[5] + ""
                                if (colorss.length > 0) {
                                    liColor = "color: " + colorss[dataPointIndex] + ""
                                }


                                return '<ul style="margin-right: 30px;margin-top: 15px;list-style-type: none">' +
                                    '<li class="tooltip-custom" style="' + liColor + '"><b>' + resp[4]['code'] + '</b>  :   ' + data + ' ' + resp[4]['unit'] + '</li>' +
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
                                colorStops: generateColors2(yValues[0], resp[4]['code'])
                            },
                        },
                        labels: xValues


                    })
                    document.getElementById('parameter_name').innerHTML = resp[4]['name'];
                    $loadingLine.hide();
                },
                error: function (xhr, b, c) {
                    console.log("xhr=" + xhr + " b=" + b + " c=" + c);
                    $loadingLine.hide();
                }
            });
        }
    </script>
    <script>
        var xValues = {!! json_encode($xValues, JSON_HEX_TAG) !!};
        var yValues = {!! json_encode($paraValues[0], JSON_HEX_TAG) !!};
        var colorss = {!! json_encode($multiColor, JSON_HEX_TAG) !!};
        var units1 = [];
        @foreach($devFactory->device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
            units1["{{$parameter->code}}"] = "{{$parameter->unit}}"

        @endforeach
        const generateColors = (data) => {
            return data.map((d, idx) => {
                var color = "{{$firstParameter->pivot->color}}"
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
                    var liColor = "color: " + "{{$firstParameter->pivot->color}}" + ""
                    if (colorss.length > 0) {
                        liColor = "color: " + colorss[dataPointIndex] + ""
                    }
                    // var liColor = "color: " + colorss[dataPointIndex] + ""

                    return '<ul style="margin-right: 30px;margin-top: 15px;list-style-type: none">' +
                        '<li class="tooltip-custom" style="' + liColor + '"><b>{{$firstParameter->code}} </b>  :   ' + data + ' ' + "{{$firstParameter->unit}}" + '</li>' +
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
@endpush
