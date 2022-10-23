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

        .legend-2 div span {
            border-radius: 50%;
            display: inline-block;
            height: 10px;
            margin-right: 5px;
            width: 10px;
        }

        .device-types-name .nav-item span{
            color: #00989d;
        }

        .device-types-name .active span{
            color: #ffffff;
        }

        .device-types-name .active {
            background-color: #00989d!important;
        }
        .no-device-in-type{
            margin: 50px;
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
            @include('admin.dashboard.locations')
            @include('admin.dashboard.device_status')
        </div>
    </div>
@endsection

@push('scripts')


    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script>
        var countOfDevice = {{count($devices)}}
        console.log(countOfDevice)

        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoYWIyMiIsImEiOiJja3Zud2NjeG03cGk1MnBxd3NrMm5kaDd4In0.vsQXgdGOH8KQ91g4rHkvUA';
        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [{{$long}},{{$lat}}],
            zoom: 3
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
                                'description': warning == 0 ?
                                    '<div id="state-legend" class="legend">' +
                                    '<h4 style="color : #000000">{{__('message.Last Read')}}</h4>' +
                                    '<h5>{{$device->name}}</h5>' +
                                    @if($device->deviceType != null)
                                        @foreach($device->deviceType->deviceParameters as $key1=>$parameter)
                                        '<div><span ></span>{{$parameter->name}}' +
                                    ':{{count($device->deviceParameters) > 0 ? json_decode($device->deviceParameters->last()->parameters, true)[$parameter->code]. " (". $parameter->unit .") " : "No Data"}}' +
                                    '</div>' +
                                    @endforeach
                                        @endif

                                        '<span>{{count($device->deviceParameters) > 0 ? \Carbon\Carbon::parse($device->deviceParameters->last()->time_of_read)->setTimezone('Asia/Damascus')->format('Y-d-m h:i a') : ""}}</span>' +
                                        '</div>' :
                                    '<div>' +
                                    '<h4 style="color : #000000">{{__('message.Last Read')}}</h4>' +
                                    '<h5>{{$device->name}}</h5>' +
                                    @if($device->deviceType != null)
                                        @foreach($device->deviceType->deviceParameters as $key2=>$parameter)
                                        '<div><span style ="color:{{$lastdangerRead[$key][$key2]}}!important">{{$parameter->name}}</span>' +
                                    ':<span style ="color:{{$lastdangerRead[$key][$key2]}}!important">{{$lastMinDanger[$key] != null ? json_decode($lastMinDanger[$key]->parameters, true)[$parameter->code]. " (". $parameter->unit .") " : "No Data"}}</span>' +
                                    '</div>' +
                                    @endforeach
                                        @endif

                                        '<span>{{count($device->deviceParameters) > 0 ? \Carbon\Carbon::parse($device->deviceParameters->last()->time_of_read)->setTimezone('Asia/Damascus')->format('Y-d-m h:i a') : ""}}</span>' +
                                        '</div>'
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
