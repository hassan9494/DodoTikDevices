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

        .device-types-name .nav-item span {
            color: #00989d;
        }

        .device-types-name .active span {
            color: #ffffff;
        }

        .device-types-name .active {
            background-color: #00989d !important;
        }

        .no-device-in-type {
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
            @include('admin.dashboard.device_status', [
                'types' => $types,
                'devicesByType' => $devicesByType,
                'untypedDevices' => $untypedDevices ?? [],
            ])
        </div>
    </div>
@endsection

@push('scripts')


    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script>
        const devices = @json($devicesPayload);
        const centroid = @json($centroid);

        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoYWIyMiIsImEiOiJja3Zud2NjeG03cGk1MnBxd3NrMm5kaDd4In0.vsQXgdGOH8KQ91g4rHkvUA';
        const initialCenter = [
            centroid && centroid.lng !== null ? centroid.lng : 0,
            centroid && centroid.lat !== null ? centroid.lat : 0,
        ];

        const zoomLevel = devices.length > 0 ? (devices.length > 50 ? 3 : 4.5) : 2;

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: initialCenter,
            zoom: zoomLevel
        });

        map.on('load', () => {
            const features = devices
                .filter(device => device.coordinates && device.coordinates.lng !== null && device.coordinates.lat !== null)
                .map(device => ({
                    type: 'Feature',
                    properties: {
                        id: device.id,
                        name: device.name,
                        status: device.status,
                        warning_count: device.warning_count || 0,
                        last_reading_formatted: device.last_reading ? device.last_reading.formatted_time : '',
                        parameters: JSON.stringify(device.parameters || [])
                    },
                    geometry: {
                        type: 'Point',
                        coordinates: [device.coordinates.lng, device.coordinates.lat]
                    }
                }));

            map.addSource('dashboard-devices', {
                type: 'geojson',
                data: {
                    type: 'FeatureCollection',
                    features: features
                }
            });

            map.addLayer({
                id: 'dashboard-devices-layer',
                type: 'circle',
                source: 'dashboard-devices',
                paint: {
                    'circle-color': [
                        'case',
                        ['>', ['get', 'warning_count'], 0],
                        'rgba(255, 100, 100, 1)',
                        '#00989d'
                    ],
                    'circle-radius': 10,
                    'circle-stroke-width': 2,
                    'circle-stroke-color': '#ffffff'
                }
            });

            const popup = new mapboxgl.Popup({
                closeButton: false,
                closeOnClick: false
            });

            map.on('mouseenter', 'dashboard-devices-layer', (event) => {
                map.getCanvas().style.cursor = 'pointer';
                const feature = event.features[0];
                const coordinates = feature.geometry.coordinates.slice();
                const properties = feature.properties;
                const parameters = JSON.parse(properties.parameters || '[]');

                const parametersMarkup = parameters.map(parameter => {
                    const value = parameter.value !== null && parameter.value !== undefined
                        ? `${parameter.value} ${parameter.unit ? `(${parameter.unit})` : ''}`
                        : '{{ __('message.No Data') }}';

                    return `<div>
                        <span style="color:${parameter.color}">${parameter.name}</span>: ${value}
                    </div>`;
                }).join('');

                const html = `
                    <div class="legend">
                        <h4 style="color:#000000">{{ __('message.Last Read') }}</h4>
                        <h5>${properties.name}</h5>
                        ${parametersMarkup}
                        <span>${properties.last_reading_formatted || ''}</span>
                    </div>
                `;

                while (Math.abs(event.lngLat.lng - coordinates[0]) > 180) {
                    coordinates[0] += event.lngLat.lng > coordinates[0] ? 360 : -360;
                }

                popup.setLngLat(coordinates).setHTML(html).addTo(map);
            });

            map.on('mouseleave', 'dashboard-devices-layer', () => {
                map.getCanvas().style.cursor = '';
                popup.remove();
            });
        });

    </script>
@endpush
