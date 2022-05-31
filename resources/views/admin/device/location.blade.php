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
                    <div class="card-header border-0 pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span
                                class="card-label font-weight-bolder text-dark">{{__('message.Device Location')}}</span>
                        </h3>



                    </div>


                    <div class="card-body pt-2" style="position: relative;">
                        <div class="col-md-12 ">

                            <form action="{{ route('admin.devices.update_location',$device->id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-1"></div>
                                    <div class="col-md-5">
                                        <label for="longitude">{{__('message.Longitude')}} :</label>
                                        <input type="text" class="form-control" name="longitude" id="longitude"
                                               value="{{$device->longitude}}">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="latitude">{{__('message.Latitude')}} :</label>
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
