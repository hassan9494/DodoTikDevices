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

    <form action="{{ route('admin.devices.update',$device->id) }}" method="POST">
        @csrf

        <div class="form-groups">

            <div class="form-group ml-5">
                <label for="name" class="col-sm-2 col-form-label">{{__('message.Name')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="name" placeholder="name" id="name"
                           class="form-control {{$errors->first('name') ? "is-invalid" : "" }} "
                           value="{{old('name') ? old('name') : $device->name}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                </div>
            </div>
            <div class="form-group ml-5">
                <label for="device_id" class="col-sm-2 col-form-label">{{__('message.device_id')}} </label>
                <div class="col-sm-9">
                    <input type="text" name="device_id" placeholder="device_id" id="device_id"
                           class="form-control {{$errors->first('device_id') ? "is-invalid" : "" }} "
                           value="{{old('device_id') ? old('device_id') : $device->device_id}}">
                    <div class="invalid-feedback">
                        {{ $errors->first('device_id') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group ml-5">
            <label for="type" class="col-sm-4 col-form-label">{{__('message.type')}}</label>
            <div class="col-sm-9">
                <select name='type' class="form-control {{$errors->first('type') ? "is-invalid" : "" }}"
                        id="parameters">
                    <option selected disabled>chose one</option>
                    @foreach ($types as $type)
                        <option
                            {{$device->type_id == $type->id ? "selected" : ""}} value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ $errors->first('type') }}
                </div>
            </div>
        </div>
        <div class="form-group ml-5">
            <label for="time_between_two_read" class="col-sm-2 col-form-label">{{__('message.time_between_two_read')}} </label>
            <div class="col-sm-9">
                <input type="number" name="time_between_two_read" placeholder="Time Between Two Read In Minute" id="time_between_two_read"
                       class="form-control {{$errors->first('time_between_two_read') ? "is-invalid" : "" }} "
                       value="{{old('time_between_two_read') ? old('time_between_two_read') : $device->time_between_two_read}}">
                <div class="invalid-feedback">
                    {{ $errors->first('time_between_two_read') }}
                </div>
            </div>
        </div>
        <div class="form-group ml-5">
            <div id="map"></div>
            <pre id="coordinates" class="coordinates"></pre>
            <input type="hidden" name="longitude" id="longitude" value="{{$device->longitude}}">
            <input type="hidden" name="latitude" id="latitude" value="{{$device->latitude}}">
        </div>
        <div class="form-group col-md-12">
            <div class="col-sm-3">
                <button type="submit" class="btn btn-info">{{__('message.Update')}}</button>
            </div>
        </div>

    </form>
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
            zoom: 5 // starting zoom
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
        $(document).ready(function () {
            $('#parameters').select2({
                placeholder: "Choose Some parameters"
            });
        });
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#settings').select2({
                placeholder: "Choose Some settings"
            });
        });
        $(function () {
            $('.selectpicker').selectpicker();
        });
    </script>
@endpush
