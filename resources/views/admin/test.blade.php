@extends('layouts.admin')

@section('styles')

    <style type="text/css">

        #mymap {

            border: 1px solid red;

            width: 800px;

            height: 500px;

        }

    </style>

@endsection

@section('content')
    <!-- Page Heading -->
    <h1>Laravel 5 - Multiple markers in google map using gmaps.js</h1>


    <div id="mymap"></div>
@endsection

@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script src="http://maps.google.com/maps/api/js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gmaps.js/0.4.24/gmaps.js"></script>

    <script type="text/javascript">


        var locations = <?php print_r(json_encode($locations)) ?>;


        var mymap = new GMaps({

            el: '#mymap',

            lat: 21.170240,

            lng: 72.831061,

            zoom: 6

        });


        $.each(locations, function (index, value) {

            mymap.addMarker({

                lat: value.lat,

                lng: value.lng,

                title: value.city,

                click: function (e) {

                    alert('This is ' + value.city + ', gujarat from India.');

                }

            });

        });


    </script>

@endpush



<head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map {
            height: 400px;
        }
    </style>
</head>
<body>
<div id="map"></div>
<script>
    function initialize() {

        // Create a LatLng object
        // We use this LatLng object to center the map and position the marker
        var center = new google.maps.LatLng(30, 20);

        // Declare your map options
        var mapOptions = {
            zoom: 4,
            center: center,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        // Create a map in the #map HTML element, using the declared options
        var map = new google.maps.Map(document.getElementById("map"), mapOptions);

        // Create a marker and place it on the map
        var marker = new google.maps.Marker({
            position: center,
            map: map
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBlG1CNBCt6G6UfCgH0paKINdVCU-W4WZI&callback=initialize" async defer></script>
</body>
