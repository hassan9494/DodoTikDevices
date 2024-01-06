@extends('layouts.admin')

@section('styles')
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

    <form action="{{ route('admin.files.exportToDatasheet') }}" method="POST">
        @csrf

        <div class="custom-date" id="custom-date">

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <label for="from" class="form-label">{{__('message.From')}}</label>
                        <input id="from"  type="date" name="from"
                               class="form-control {{$errors->first('from') ? "is-invalid" : "" }} "
                               value="{{old('from')}}">
                        <div class="invalid-feedback">
                            {{ $errors->first('from') }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="to" class="form-label">{{__('message.To')}}</label>
                        <input id="to"  type="date" name="to"
                               class="form-control {{$errors->first('to') ? "is-invalid" : "" }} "
                               value="{{old('to')}}">
                        <div class="invalid-feedback">
                            {{ $errors->first('to') }}
                        </div>
                    </div>
                    <div class="col-md-12" style="margin-top: 15px">
                        <input id="submit" name="id" type="hidden" value="{{$ftpFile->id}}">

                        <button type="submit" class="btn btn-info">{{__('message.Export')}}</button>
                    </div>
                </div>


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
