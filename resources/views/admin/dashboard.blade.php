@extends('layouts.admin')

@section('styles')

    <link href="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.css" rel="stylesheet">
    <style>
        body { margin: 0; padding: 0; }
        #map { width: 100%;height: 400px; }
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

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>{{__('message.No.')}}</th>

                    <th>{{__('message.settings')}}</th>

                </tr>

                </thead>

                <tbody>

                @php

                    $no=0;

                @endphp

                @foreach ($tests as $test)
                    @if(auth()->user()->role=='Administrator')
                        <tr>
                            <td>{{ ++$no }}</td>
                            <td>{{json_decode($test->settings) }}</td>

                        </tr>
                    @endif
                @endforeach

                </tbody>

            </table>

        </div>

    </div>
@endsection

@push('scripts')

    <script src="https://api.mapbox.com/mapbox-gl-js/v2.7.0/mapbox-gl.js"></script>
    <script>
        mapboxgl.accessToken = 'pk.eyJ1Ijoic2FoYWIyMiIsImEiOiJja3Zud2NjeG03cGk1MnBxd3NrMm5kaDd4In0.vsQXgdGOH8KQ91g4rHkvUA';
        const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v11', // style URL
            center: [0, 0], // starting position [lng, lat]
            zoom: 5 // starting zoom
        });

        const marker = new mapboxgl.Marker({
            draggable: true
        })
            .setLngLat([0, 0])
            .addTo(map);

        function onDragEnd() {
            const lngLat = marker.getLngLat();
            coordinates.style.display = 'block';
            coordinates.innerHTML = `Longitude: ${lngLat.lng}<br />Latitude: ${lngLat.lat}`;
        }

        marker.on('dragend', onDragEnd);
        // Create a default Marker and add it to the map.
        const marker1 = new mapboxgl.Marker()
            .setLngLat([12.554729, 55.70651])
            .addTo(map);
        const marker3 = new mapboxgl.Marker()
            .setLngLat([15.554729, 55.70651])
            .addTo(map);
        const marker4 = new mapboxgl.Marker()
            .setLngLat([14.554729, 55.70651])
            .addTo(map);

        // Create a default Marker, colored black, rotated 45 degrees.
        const marker2 = new mapboxgl.Marker({ color: 'black', rotation: 45 })
            .setLngLat([12.65147, 55.608166])
            .addTo(map);
    </script>

{{--    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBFE47G8Hie0gWOuFRPnzUnA8clIWvgpWc&callback=initialize" async defer></script>--}}

{{--    <script>--}}
{{--        function initialize() {--}}

{{--            // Create a LatLng object--}}
{{--            // We use this LatLng object to center the map and position the marker--}}
{{--            var center = new google.maps.LatLng(30, 20);--}}

{{--            // Declare your map options--}}
{{--            var mapOptions = {--}}
{{--                zoom: 8,--}}
{{--                center: center,--}}
{{--                mapTypeId: google.maps.MapTypeId.ROADMAP--}}
{{--            };--}}

{{--            // Create a map in the #map HTML element, using the declared options--}}
{{--            var map = new google.maps.Map(document.getElementById("map"), mapOptions);--}}

{{--            // Create a marker and place it on the map--}}
{{--            var marker = new google.maps.Marker({--}}
{{--                position: center,--}}
{{--                map: map--}}
{{--            });--}}
{{--        }--}}

{{--        // let map;--}}
{{--        //--}}
{{--        // function initMap() {--}}
{{--        //     map = new google.maps.Map(document.getElementById("map"), {--}}
{{--        //         center: { lat: -34.397, lng: 150.644 },--}}
{{--        //         zoom: 8,--}}
{{--        //     });--}}
{{--        // }--}}
        {{--var locations = <?php print_r(json_encode($locations)) ?>;--}}


        {{--var mymap = new GMaps({--}}

        {{--    el: '#mymap',--}}

        {{--    lat: 21.170240,--}}

        {{--    lng: 72.831061,--}}

        {{--    zoom: 6--}}

        {{--});--}}


        {{--$.each(locations, function (index, value) {--}}

        {{--    mymap.addMarker({--}}

        {{--        lat: value.lat,--}}

        {{--        lng: value.lng,--}}

        {{--        title: value.city,--}}

        {{--        click: function (e) {--}}

        {{--            alert('This is ' + value.city + ', gujarat from India.');--}}

        {{--        }--}}

        {{--    });--}}

        {{--});--}}


{{--    // </script>--}}

@endpush
