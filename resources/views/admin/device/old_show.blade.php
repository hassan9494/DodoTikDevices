@extends('layouts.admin')
@section('styles')

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
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
        <div class="row">
            <div class="col-md-12 ">
                <h1 class="text-center">{{__('message.Name')}} : {{$device->name}} </h1>
                <h1 class="text-center">{{__('message.device_id')}} : {{$device->device_id}} </h1>
                <h1 class="text-center">{{__('message.type')}} : {{$device->deviceType->name}} </h1>
            </div>
            <canvas id="speedChart" style="width:100%;max-width:100%;height: 375px"></canvas>
            @if(count($device->deviceType->deviceSettings) != 0)
            <div class="col-md-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h1>{{__('message.settings')}} : </h1>
                    </div>
                    <div class="card-body">

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
            @endif
{{--            @foreach($device->deviceType->deviceParameters as $parameter)--}}
{{--                <h1>{{$parameter->name}}</h1>--}}
{{--                <canvas id="myChart_{{$parameter->name}}" style="width:100%;max-width:100%;height: 375px"></canvas>--}}
{{--            @endforeach--}}

            {{--            <div class="col-md-12">--}}
            {{--                <div class="card shadow mb-4">--}}
            {{--                    <div class="card-header py-3">--}}
            {{--                        <h1>{{__('message.parameters')}} : </h1>--}}
            {{--                    </div>--}}
            {{--                    <div class="card-body">--}}

            {{--                        <div class="table-responsive">--}}
            {{--                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">--}}
            {{--                                <thead>--}}
            {{--                                <tr>--}}
            {{--                                    @foreach($device->deviceType->deviceParameters as $parameter)--}}
            {{--                                        <th>{{$parameter->name}}</th>--}}
            {{--                                    @endforeach--}}
            {{--                                    <th>{{__('message.Time')}}</th>--}}
            {{--                                </tr>--}}
            {{--                                </thead>--}}
            {{--                                <tbody>--}}
            {{--                                @foreach($device->deviceParameters as $para)--}}
            {{--                                <tr>--}}

            {{--                                    @if($device->deviceParameters == null)--}}
            {{--                                        @foreach($device->deviceParameters as $parameter)--}}
            {{--                                            <td>{{$parameter}}</td>--}}
            {{--                                        @endforeach--}}
            {{--                                    @else--}}
            {{--                                        @foreach($device->deviceType->deviceParameters as $parameter)--}}
            {{--                                            <td>{{json_decode($para->parameters,true)[$parameter->name] }}</td>--}}

            {{--                                        @endforeach--}}
            {{--                                    @endif--}}
            {{--                                    <td>--}}
            {{--                                       {{$para->time_of_read}}--}}

            {{--                                    </td>--}}
            {{--                                </tr>--}}
            {{--                                @endforeach--}}
            {{--                                </tbody>--}}
            {{--                            </table>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}
            <div class="col-md-12 ">
                <div id="map"></div>
                <pre id="coordinates" class="coordinates"></pre>
                <form action="{{ route('admin.devices.update_location',$device->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="longitude" id="longitude" value="{{$device->longitude}}">
                    <input type="hidden" name="latitude" id="latitude" value="{{$device->latitude}}">
                    <input type="submit" value="Save Location" class="btn btn-primary">
                </form>
            </div>

        </div>
    </div>
@endsection


@push('scripts')
    <script>
        var speedCanvas = document.getElementById("speedChart");
        var yValues = {!! json_encode($paraValues, JSON_HEX_TAG) !!};
        var xValues = {!! json_encode($xValues, JSON_HEX_TAG) !!};

        Chart.defaults.global.defaultFontFamily = "Lato";
        Chart.defaults.global.defaultFontSize = 18;
        var x = [];
        var colors = ["red","blue","green","black","brown","yellow","grey","pink","purbel"]
        @foreach($device->deviceType->deviceParameters as $key=>$parameter)
        var data_{{$key}} = {
            label: "{{$parameter->name}}",
            data: yValues[{{$key}}],
            lineTension: 0,
            fill: false,
            borderColor:colors[{{$key}}],
            unit : "tst",
            {{--backgroundColor: colors[{{$key}}],--}}
        };
        x.push(data_{{$key}})
        @endforeach


        var speedData = {
            labels: xValues,
            datasets: x
        };

        var chartOptions = {
            elements: {
                point: {
                    radius: 2
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0,
                        max: 50
                    }
                }],
            },
            legend: {
                display: true,
                position: 'top',
                labels: {
                    boxWidth: 80,
                    fontColor: 'black'
                }
            },
            tooltips: {
                enabled: true,
                mode: 'single',
                callbacks: {
                    label: function(tooltipItems, data) {
                        var text = tooltipItems.datasetIndex === 0 ? 'g/m³' : tooltipItems.datasetIndex === 1 ? '°' : tooltipItems.datasetIndex === 2 ? 'Volt' : 'mq2'
                        return tooltipItems.yLabel + ' ' + text;
                    }
                }
            }

        };

        var lineChart = new Chart(speedCanvas, {
            type: 'line',
            data: speedData,
            options: chartOptions
        });
    </script>
{{--    <script>--}}
{{--        var xValues = {!! json_encode($xValues, JSON_HEX_TAG) !!};--}}
{{--        var yValues = {!! json_encode($paraValues, JSON_HEX_TAG) !!};--}}
{{--        @foreach($device->deviceType->deviceParameters as $key=>$parameter)--}}
{{--        new Chart("myChart_{{$parameter->name}}", {--}}
{{--            type: "line",--}}
{{--            data: {--}}
{{--                labels: xValues,--}}
{{--                datasets: [--}}
{{--                    {--}}
{{--                        label: 'Dataset 1',--}}
{{--                        fill: false,--}}
{{--                        lineTension: 0,--}}
{{--                        backgroundColor: "rgba(0,0,255,1.0)",--}}
{{--                        borderColor: "rgba(0,0,255,0.1)",--}}
{{--                        data: yValues[{{$key}}]--}}
{{--                    },--}}

{{--                ]--}}
{{--            },--}}
{{--            options: {--}}
{{--                responsive : true,--}}
{{--                legend: {display: false},--}}
{{--                plugins: {--}}
{{--                    legend: {--}}
{{--                        position: 'top',--}}
{{--                    },--}}
{{--                    title: {--}}
{{--                        display: true,--}}
{{--                        text: 'Chart.js Line Chart'--}}
{{--                    }--}}
{{--                },--}}
{{--                elements: {--}}
{{--                    point: {--}}
{{--                        radius: 0--}}
{{--                    }--}}
{{--                },--}}
{{--                scales: {--}}
{{--                    yAxes: [{--}}
{{--                        ticks: {--}}
{{--                            min: 0,--}}
{{--                            max: 100--}}
{{--                        }--}}
{{--                    }],--}}
{{--                }--}}
{{--            }--}}
{{--        });--}}
{{--        console.log(yValues[{{$key}}])--}}
{{--        @endforeach--}}
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
    <script>
        // Prepare the preview for profile picture
        $("#wizard-picture").change(function () {
            readURL(this);
        });

        //Function to show image before upload
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#wizardPicturePreview').attr('src', e.target.result).fadeIn('slow');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    {{--    <script>--}}
    {{--        $(document).ready(function () {--}}
    {{--            $('#parameters').select2({--}}
    {{--                placeholder: "Choose Some parameters"--}}
    {{--            });--}}
    {{--        });--}}
    {{--        $(function () {--}}
    {{--            $('.selectpicker').selectpicker();--}}
    {{--        });--}}
    {{--    </script>--}}
    {{--    <script>--}}
    {{--        $(document).ready(function () {--}}
    {{--            $('#settings').select2({--}}
    {{--                placeholder: "Choose Some settings"--}}
    {{--            });--}}
    {{--        });--}}
    {{--        $(function () {--}}
    {{--            $('.selectpicker').selectpicker();--}}
    {{--        });--}}
    {{--    </script>--}}
@endpush
