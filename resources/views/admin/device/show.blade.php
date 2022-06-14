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

        .spinner-border {
            position: relative;
            bottom: 300px;
            width: 5rem;
            height: 5rem;
            display: none;
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

        .legend {
            background-color: #fff;
            border-radius: 3px;
            top: 30px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            padding: 10px;
            position: absolute;
            right: 10px;
            z-index: 1;
        }

        .legend h4 {
            margin: 0 0 10px;
        }

        .legend div {
            text-align: left;
        }

        .legend div p {
            border-radius: 50%;
            display: inline-block;
            height: 10px;
            margin-right: 5px;
            width: 10px;
        }


        .legend-2 {
            background-color: #fff;
            border-radius: 3px;
            top: 30px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
            padding: 10px;
            position: absolute;
            left: 10px;
            z-index: 1;
        }

        .legend-2 h4 {
            margin: 0 0 10px;
        }

        .legend-2 div {
            text-align: left;
        }

        .legend-2 div span {
            border-radius: 50%;
            display: inline-block;
            height: 10px;
            margin-right: 5px;
            width: 10px;
        }

        .mapboxgl-popup {
            max-width: 500px;
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
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
                                class="card-label font-weight-bolder text-dark">{{__('message.Device Location')}}</span>
                        </h3>

                        <div class="card-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">

                                <li class="nav-item nav-item">
                                    <a href="{{route('admin.devices.location', [$device->id])}}" role="tab"
                                       data-rb-event-key="Location"
                                       aria-selected="true"
                                       class="nav-link py-2 px-4  nav-link active ">{{__('message.Edit Location')}}</a></li>
                            </ul>
                        </div>

                    </div>


                    <div class="card-body pt-2" style="position: relative;">
                        <div class="col-md-12 ">


                            <div class="row">
                                <div class="col-md-12" style="margin-top: 15px;margin-bottom: 15px">
                                    <div id="map"></div>
                                    <pre id="coordinates" class="coordinates"></pre>
                                    @if(count($xValues) > 0)
                                        <div id="state-legend" class="legend" style="display: none">
                                            <h4>{{__('message.Last Read')}}</h4>
                                            @foreach($device->deviceType->deviceParameters as $key=>$parameter)
                                                <div><p style=""></p>
                                                    <span style="color: {{$dangerColor[$key]}}">{{$parameter->name}}</span>
                                                    :<span style="color: {{$dangerColor[$key]}}">{{$paraValues[$key][count($paraValues[$key]) - 1]}}  ({{$parameter->unit}})</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


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
                                    @foreach($device->deviceType->deviceParameters as $key=>$parameter)

                                {
                                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                    data: data[0][{{$key}}]
                                },
                                @endforeach],
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
                                    @foreach($device->deviceType->deviceParameters as $key=>$parameter)

                                {
                                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                                    data: data[0][{{$key}}]
                                },
                                @endforeach],
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
                    @foreach($device->deviceType->deviceParameters as $key=>$parameter)

                {
                    name: "{{$parameter->name}} (" + "{{$parameter->unit}}" + ")",
                    data: yValues[{{$key}}]
                },
                @endforeach ],
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

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoYWIyMiIsImEiOiJja3Zud2NjeG03cGk1MnBxd3NrMm5kaDd4In0.vsQXgdGOH8KQ91g4rHkvUA';
        var long = {{$device->longitude}};
        var lat = {{$device->latitude}};
        const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v11', // style URL
            center: [long, lat], // starting position [lng, lat]
            zoom: 10 // starting zoom
        });

        var warning =
        {{$warning}}
        const size = 200;

        const pulsingDot = {
            width: size,
            height: size,
            data: new Uint8Array(size * size * 4),
            onAdd: function () {
                const canvas = document.createElement('canvas');
                canvas.width = this.width;
                canvas.height = this.height;
                this.context = canvas.getContext('2d');
            },
            render: function () {
                const duration = 1000;
                const t = (performance.now() % duration) / duration;

                const radius = (size / 2) * 0.3;
                const outerRadius = (size / 2) * 0.7 * t + radius;
                const context = this.context;

// Draw the outer circle.
                context.clearRect(0, 0, this.width, this.height);
                context.beginPath();
                context.arc(
                    this.width / 2,
                    this.height / 2,
                    outerRadius,
                    0,
                    Math.PI * 2
                );
                context.fillStyle = warning != 1 ? `rgba(255, 200, 200, ${1 - t})` : '#00989d11';
                context.fill();

// Draw the inner circle.
                context.beginPath();
                context.arc(
                    this.width / 2,
                    this.height / 2,
                    radius,
                    0,
                    Math.PI * 2
                );
                context.fillStyle = warning != 1 ? 'rgba(255, 100, 100, 1)' : '#00989d';
                context.strokeStyle = 'white';
                context.lineWidth = 2 + 4 * (1 - t);
                context.fill();
                context.stroke();

// Update this image's data with data from the canvas.
                this.data = context.getImageData(
                    0,
                    0,
                    this.width,
                    this.height
                ).data;

// Continuously repaint the map, resulting
// in the smooth animation of the dot.
                map.triggerRepaint();

// Return `true` to let the map know that the image was updated.
                return true;
            }
        };
        map.on('load', () => {
            map.addImage('pulsing-dot', pulsingDot, {pixelRatio: 3});


            map.addSource('dot-point', {
                'type': 'geojson',
                'data': {
                    'type': 'FeatureCollection',
                    'features': [
                        {
                            'type': 'Feature',
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [long, lat] // icon position [lng, lat]
                            }
                        }
                    ]
                }
            });
            map.addSource('places', {
                'type': 'geojson',
                'data': {
                    'type': 'FeatureCollection',
                    'features': [
                        {
                            'type': 'Feature',
                            'properties': {
                                'description':
                                    '<div id="state-legend" class="legend">' +
                                    '<h5>Last_Read</h5>' +
                                    '</div>'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [long, lat]
                            }
                        },
                    ]
                }
            });

            map.addLayer({
                'id': 'layer-with-pulsing-dot',
                'type': 'symbol',
                'source': 'dot-point',
                'layout': {
                    'icon-image': 'pulsing-dot'
                }
            });
            map.addLayer({
                'id': 'places',
                'type': 'circle',
                'source': 'places',
                'paint': {
                    'circle-color': warning != 1 ? 'rgba(255, 100, 100, 1)' : '#00989d',
                    'circle-radius': 6,
                    'circle-stroke-width': 2,
                    'circle-stroke-color': warning != 1 ? 'rgba(255, 100, 100, 1)' : '#00989d',
                }
            });
            const popup = new mapboxgl.Popup({
                closeButton: false,
                closeOnClick: false
            });
            map.on('mouseenter', 'places', (e) => {
// Change the cursor style as a UI indicator.
                map.getCanvas().style.cursor = 'pointer';

// Copy coordinates array.
                const coordinates = e.features[0].geometry.coordinates.slice();
                const description = e.features[0].properties.description;

// Ensure that if the map is zoomed out such that multiple
// copies of the feature are visible, the popup appears
// over the copy being pointed to.
                while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
                    coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
                }
                $('#state-legend').attr('style', 'display : block')
                $('#state-legend-2').attr('style', 'display : block')

// Populate the popup and set its coordinates
// based on the feature found.
//                     popup.setLngLat(coordinates).setHTML(description).addTo(map);

            });

            map.on('mouseleave', 'places', () => {
                map.getCanvas().style.cursor = '';
                popup.remove();
                $('#state-legend').attr('style', 'display : none')
                $('#state-legend-2').attr('style', 'display : none')
            });
        });


        //
        // marker.on('dragend', onDragEnd);
        // Create a default Marker and add it to the map.
        // const marker1 = new mapboxgl.Marker()
        //     .setLngLat([12.554729, 55.70651])
        //     .addTo(map);

    </script>

@endpush
