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
        .mapboxgl-popup {
            max-width: 400px;
            font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
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

    <div class="container-fluid">
        <div class="row" style="text-align: -webkit-center;">
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                <div class="card card-custom mb-4">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Devices Locations')}}</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2" style="position: relative;">
                        <div class="col-md-12 " style="margin-top: 15px;margin-bottom: 15px">
                            <div id="map"></div>
                            <pre id="coordinates" class="coordinates"></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
                <div class="card card-custom mb-4">
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Devices Status')}}</span>
                        </h3>
                    </div>
                    <div class="card-body pt-2" style="position: relative;">
                        <div class="row">
                            @foreach($devices as $key=>$device)
                            <div class="col-md-4 " style="margin-top: 15px;margin-bottom: 15px">
                                <div class="card card-custom mb-4">
                                    <div class="card-header border-0 pt-5" style="padding-top: 1rem!important;">
                                        <h3 class="card-title align-items-start flex-column"><span
                                                class="card-label font-weight-bolder text-dark">{{$device->name}}</span></h3>
                                        <div class="card-toolbar">
                                            <ul class="nav nav-pills nav-pills-sm nav-dark-75 nav nav-test" role="tablist">
                                                <li class="nav-item nav-item">
                                                    <a title="Show" id="d_{{$device->id}}" href="{{route('admin.devices.show', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-eye"></i> </a>
{{--                                                    <a title="Edit" href="{{route('admin.devices.edit', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-edit"></i> </a>--}}
{{--                                                    <a href="{{route('admin.devices.add_device_setting_values', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-cogs"></i> </a>--}}
{{--                                                    <a href="{{route('admin.devices.add_device_limit_values', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-chart-line"></i> </a>--}}
{{--                                                    <a title="Edit Location" href="{{route('admin.devices.location', [$device->id])}}" class="btn btn-sm"> <i class="fas fa-location-arrow"></i> </a>--}}
                                                <li class="nav-item nav-item">
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body  pt-2" style="background-color: {{$state[$key] == 'Offline' ? '#ff6464' : '#00989d'}} ;    padding-top: 2rem!important;">
                                        <h4 class="device-status" style="color: #FFFFFF">{{$state[$key]}} </h4><i
                                            class="fas {{$state[$key] == "Offline" ? 'fa-times' : 'fa-check'  }}"
                                            style="font-size: 25px; color:{{$state[$key] == "Offline" ? 'red' : 'green'  }} "></i>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')


    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
{{--    <script>--}}
{{--        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoYWIyMiIsImEiOiJja3Zud2NjeG03cGk1MnBxd3NrMm5kaDd4In0.vsQXgdGOH8KQ91g4rHkvUA';--}}
{{--        const map = new mapboxgl.Map({--}}
{{--            container: 'map', // container ID--}}
{{--            style: 'mapbox://styles/mapbox/streets-v11', // style URL--}}
{{--            center: [0, 0], // starting position [lng, lat]--}}
{{--            zoom: 1 // starting zoom--}}
{{--        });--}}

{{--        @foreach($devices as $key=>$device)--}}
{{--        const marker_{{$device->device_id}} =--}}
{{--        new mapboxgl.Marker({--}}
{{--            draggable: false--}}
{{--        })--}}
{{--            .setLngLat([{{$device->longitude}}, {{$device->latitude}}])--}}
{{--            .addTo(map);--}}
{{--        @endforeach--}}
{{--    </script>--}}
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoYWIyMiIsImEiOiJja3Zud2NjeG03cGk1MnBxd3NrMm5kaDd4In0.vsQXgdGOH8KQ91g4rHkvUA';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [{{$devices[1]->longitude}}, {{$devices[1]->latitude}}],
            zoom: 10
        });

        map.on('load', () => {
            @foreach($devices as $key=>$device)
                var warning = {{$warning[$key]}};
            map.addSource('places_{{$device->id}}', {
                'type': 'geojson',
                'data': {
                    'type': 'FeatureCollection',
                    'features': [

                        {
                            'type': 'Feature',
                            'properties': {
                                'description':warning == 0 ?
                                    '<strong>Last Read</strong><p>{{count($device->deviceParameters) > 0 ? $device->deviceParameters->last()->parameters . " at " .$device->deviceParameters->last()->time_of_read: 'No Data'}}</p>'
                                    : '<strong>Last Danger Max Read</strong><p>{{$lastMaxDanger[$key] != null ? $lastMaxDanger[$key]->parameters . " at " .$lastMaxDanger[$key]->time_of_read: 'No Danger Data'}}</p><strong>Last Danger Min Read</strong><p>{{$lastMinDanger[$key] != null ? $lastMinDanger[$key]->parameters . " at " .$lastMinDanger[$key]->time_of_read: 'No Danger Data'}}</p>'
                            },
                            'geometry': {
                                'type': 'Point',
                                'coordinates': [{{$device->longitude}}, {{$device->latitude}}]
                            }
                        },

                    ]
                }
            });
// Add a layer showing the places.
            map.addLayer({
                'id': 'places_{{$device->id}}',
                'type': 'circle',
                'source': 'places_{{$device->id}}',
                'paint': {
                    'circle-color': warning == 0 ? '#00989d' : 'rgba(255, 100, 100, 1)',
                    'circle-radius': 10,
                    'circle-stroke-width': 2,
                    'circle-stroke-color': '#ffffff'
                }
            });

// Create a popup, but don't add it to the map yet.
            const popup_{{$device->id}} = new mapboxgl.Popup({
                closeButton: false,
                closeOnClick: false
            });

            map.on('mouseenter', 'places_{{$device->id}}', (e) => {
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

// Populate the popup and set its coordinates
// based on the feature found.
                popup_{{$device->id}}.setLngLat(coordinates).setHTML(description).addTo(map);
            });

            map.on('mouseleave', 'places_{{$device->id}}', () => {
                map.getCanvas().style.cursor = '';
                popup_{{$device->id}}.remove();
            });
            @endforeach
        });

    </script>
@endpush
