@extends('layouts.admin')
@section('styles')

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Roboto, sans-serif;
        }

        #chart {
            max-width: 100%;
            margin: 35px auto;
        }

        #map {
            width: 100%;
            height: 400px;
        }
    </style>
    <style>
        .coordinates {
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            position: absolute;
            bottom: 40px;
            left: 10px;
            padding: 5px 10px;
            margin: 0;
            font-size: 11px;
            line-height: 18px;
            border-radius: 3px;
            display: none;
        }
    </style>

@endsection
@section('content')

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="container-fluid">
        <div class="row" style="text-align: -webkit-center;">
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                <div class="card card-custom mb-4">
                    <div class="card-header border-0 pt-5"><h3 class="card-title align-items-start flex-column"><span
                                class="card-label font-weight-bolder text-dark">{{__('message.device_id')}} : {{$device->device_id}} </span>
                            <span
                                class="card-label font-weight-bolder text-dark" style="margin-top: 15px">{{__('message.type')}} : {{$device->deviceType->name}}  </span>
                        </h3>

                        <div class="card-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav" role="tablist">
                                <li class="nav-item nav-item">
                                    <a href="#" role="tab" data-rb-event-key="Custom"
                                       tabindex="-1" aria-selected="false"
                                       class="nav-link py-2 px-4   nav-link" id="custom">Custom</a></li>
                                <li class="nav-item nav-item">
                                    <a href="#" role="tab" data-rb-event-key="ThisMonth"
                                       tabindex="-1" aria-selected="false"
                                       class="nav-link py-2 px-4   nav-link">This Month</a>
                                </li>
                                <li class="nav-item nav-item">
                                    <a href="#" role="tab" data-rb-event-key="ThisWeek"
                                       tabindex="-1" aria-selected="false"
                                       class="nav-link py-2 px-4   nav-link">This Week</a>
                                </li>
                                <li class="nav-item nav-item">
                                    <a href="#" role="tab" data-rb-event-key="ThisDay"
                                       aria-selected="true"
                                       class="nav-link py-2 px-4  active nav-link active">This
                                        Day</a></li>
                            </ul>
                        </div>

                    </div>
                    <div class="custom-date" id="custom-date" style="display: none">

                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="from" class="form-label">From</label>
                                        <input id="from" type="date" name="from" class="form-control" value="{{\Carbon\Carbon::now()->format("Y-m-01")}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="to" class="form-label">To</label>
                                        <input id="to" type="date" name="to" class="form-control" value="{{\Carbon\Carbon::now()->format("Y-m-t")}}">
                                    </div>
                                </div>


                            </div>
                            <div class="col-md-2"></div>

                    </div>

                    <div class="card-body pt-2" style="position: relative;">
                        <div id="chart">
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
                                                        <td>{{json_decode($device->deviceSetting->settings,true)[$setting->name] }}</td>
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
        <div class="row" style="text-align: -webkit-center;">
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                <div class="card card-custom mb-4">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">Device Location</span>
                        </h3>



                    </div>


                    <div class="card-body pt-2" style="position: relative;">
                        <div class="col-md-12 ">

                            <form action="{{ route('admin.devices.update_location',$device->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-5">
                                        <label for="longitude">Longitude :</label>
                                        <input type="text" class="form-control" name="longitude" id="longitude"
                                               value="{{$device->longitude}}">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="latitude">Latitude :</label>
                                        <input type="text" class="form-control" name="latitude" id="latitude"
                                               value="{{$device->latitude}}">
                                    </div>
                                    <div class="col-md-1"></div>

                                    <div class="col-md-1"></div>
                                    <div class="col-md-10" style="margin-top: 15px;margin-bottom: 15px">
                                        <div id="map"></div>
                                        <pre id="coordinates" class="coordinates"></pre>
                                        <input type="submit" value="Save Location" class="btn btn-primary" style="margin-top: 15px">
                                    </div>
                                    <div class="col-md-1"></div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded',function () {
            $('.nav-link').click(function (e) {

                $('#custom-date').attr('style','display :none')
            })
        });
        document.addEventListener('DOMContentLoaded',function () {
            $('#custom').click(function (e) {

                $('#custom-date').attr('style','display :block')
            })
        });
        document.addEventListener('DOMContentLoaded',function () {
            $('#from').change(function (e) {

               console.log($(this).val())
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
            @if ($parameter->code == "Humidity"){
            units.push("%")
        }
            @elseif($parameter->code == "Temperature"){
            units.push("°")
        }
            @elseif($parameter->code == "Bat_v"){
            units.push("volt")
        }
            @elseif($parameter->code == "Gas_Resistance"){
            units.push("ohm")
        }
        @endif

        @endforeach
        console.log(xVals)
        var options = {
            series: [
                    @foreach($device->deviceType->deviceParameters as $key=>$parameter)

                {
                    name: "{{$parameter->name}} (" + units[{{$key}}] + ")",
                    data: yValues[{{$key}}]
                },
                @endforeach ],
            chart: {
                height: 500,
                width: 1500,
                type: 'area'
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
                categories: xVals,
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
    {{--    <script>--}}
    {{--        var speedCanvas = document.getElementById("speedChart");--}}
    {{--        var yValues = {!! json_encode($paraValues, JSON_HEX_TAG) !!};--}}
    {{--        var xValues = {!! json_encode($xValues, JSON_HEX_TAG) !!};--}}

    {{--        // Chart.defaults.global.defaultFontFamily = "Lato";--}}
    {{--        Chart.defaults.global.defaultFontSize = 18;--}}
    {{--        var x = [];--}}
    {{--        var colors = ["red", "blue", "green", "black", "brown", "yellow", "grey", "pink", "purbel"]--}}
    {{--        @foreach($device->deviceType->deviceParameters as $key=>$parameter)--}}
    {{--        var data_{{$key}} = {--}}
    {{--            label: "{{$parameter->name}}",--}}
    {{--            data: yValues[{{$key}}],--}}
    {{--            lineTension: 0,--}}
    {{--            fill: false,--}}
    {{--            borderColor: colors[{{$key}}],--}}
    {{--            unit: "tst",--}}
    {{--            --}}{{--backgroundColor: colors[{{$key}}],--}}
    {{--        };--}}
    {{--        x.push(data_{{$key}})--}}
    {{--        @endforeach--}}


    {{--        var speedData = {--}}
    {{--            labels: xValues,--}}
    {{--            datasets: x--}}
    {{--        };--}}

    {{--        var chartOptions = {--}}
    {{--            elements: {--}}
    {{--                point: {--}}
    {{--                    radius: 1.5--}}
    {{--                }--}}
    {{--            },--}}
    {{--            scales: {--}}
    {{--                x: {--}}
    {{--                    position: 'bottom',--}}
    {{--                    grid: {--}}
    {{--                        offset: true // offset true to get labels in between the lines instead of on the lines--}}
    {{--                    }--}}
    {{--                },--}}
    {{--                x2: {--}}
    {{--                    position: 'top',--}}
    {{--                    grid: {--}}
    {{--                        offset: true // offset true to get labels in between the lines instead of on the lines--}}
    {{--                    }--}}
    {{--                },--}}
    {{--                y: {--}}
    {{--                    ticks: {--}}
    {{--                        count: (context) => (context.scale.chart.data.labels.length + 1)--}}
    {{--                    }--}}
    {{--                }--}}
    {{--            },--}}
    {{--            legend: {--}}
    {{--                display: true,--}}
    {{--                position: 'top',--}}
    {{--                labels: {--}}
    {{--                    boxWidth: 80,--}}
    {{--                    fontColor: 'black'--}}
    {{--                }--}}
    {{--            },--}}
    {{--            tooltips: {--}}
    {{--                enabled: true,--}}
    {{--                mode: 'single',--}}
    {{--                callbacks: {--}}
    {{--                    label: function (tooltipItems, data) {--}}
    {{--                        var text = tooltipItems.datasetIndex === 0 ? 'g/m³' : tooltipItems.datasetIndex === 1 ? '°' : tooltipItems.datasetIndex === 2 ? 'Volt' : 'mq2'--}}
    {{--                        return data.datasets[tooltipItems.datasetIndex].label + " : " + tooltipItems.yLabel + ' ' + text;--}}
    {{--                    }--}}
    {{--                }--}}
    {{--            }--}}

    {{--        };--}}

    {{--        var lineChart = new Chart(speedCanvas, {--}}
    {{--            type: 'line',--}}
    {{--            data: speedData,--}}
    {{--            options: chartOptions--}}
    {{--        });--}}
    {{--    </script>--}}
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoYWIyMiIsImEiOiJja3Zud2NjeG03cGk1MnBxd3NrMm5kaDd4In0.vsQXgdGOH8KQ91g4rHkvUA';
        var long = document.getElementById('longitude').value;
        var lat = document.getElementById('latitude').value;
        const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v11', // style URL
            center: [long, lat], // starting position [lng, lat]
            zoom: 10 // starting zoom
        });

        const marker = new mapboxgl.Marker({
            draggable: true
        })
            .setLngLat([long, lat])
            .addTo(map);

        function onDragEnd() {
            const lngLat = marker.getLngLat();
            coordinates.style.display = 'block';
            coordinates.innerHTML = `Longitude: ${lngLat.lng}<br />Latitude: ${lngLat.lat}`;
            document.getElementById('longitude').value = lngLat.lng;
            document.getElementById('latitude').value = lngLat.lat;
        }

        marker.on('dragend', onDragEnd);
        // Create a default Marker and add it to the map.
        // const marker1 = new mapboxgl.Marker()
        //     .setLngLat([12.554729, 55.70651])
        //     .addTo(map);

    </script>

@endpush
