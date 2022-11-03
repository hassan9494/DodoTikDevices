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
<section class="box-fancy section-fullwidth text-light p-b-0">
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
                                        <h4 style="color: #00989d ">{{__('message.Last Read')}}</h4>
                                        @if($paraValues[0] != null)
                                            @if(count($testPara) > 0)
                                                @foreach($testPara as $key=>$parameter)
                                                    <div><p style=""></p>
                                                        <span
                                                            style="color: {{$dangerColor[$key]}}">{{$parameter->name}}</span>
                                                        :<span style="color: {{$dangerColor[$key]}}">{{$paraValues[$key][count($paraValues[$key]) - 1]}}  ({{$parameter->unit}})</span>
                                                    </div>
                                                @endforeach
                                                <span
                                                    style="color: #000000">{{\Carbon\Carbon::parse($xValues[count($xValues) - 1])->setTimezone('Asia/Damascus')->format('Y-d-m h:i a')}}  </span>
                                            @else
                                                @foreach($device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                                                    <div><p style=""></p>
                                                        <span
                                                            style="color: {{$dangerColor[$key]}}">{{$parameter->name}}</span>
                                                        :<span style="color: {{$dangerColor[$key]}}">{{$paraValues[$key][count($paraValues[$key]) - 1]}}  ({{$parameter->unit}})</span>
                                                    </div>
                                                @endforeach
                                                <span
                                                    style="color: #000000">{{\Carbon\Carbon::parse($xValues[count($xValues) - 1])->setTimezone('Asia/Damascus')->format('Y-d-m h:i a')}}  </span>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <div id="state-legend" class="legend" style="display: none">
                                        <h4 style="color: #00989d ">{{__('message.Last Read')}}</h4>
                                        @if($device->deviceParameters()->orderBy('id','desc')->first() != null)
                                            @if(count($testPara) == count($device->deviceType->deviceParameters))
                                                @foreach($device->deviceType->deviceParameters()->orderBy('order')->get() as $key=>$parameter)
                                                    {{--                                                <h4>{{$key}}</h4>--}}
                                                    <div><p style=""></p>
                                                        {{--                                                    {{dd($dangerColor)}}--}}
                                                        <span
                                                            style="color: {{$dangerColor[$key]}}">{{$parameter->name}}</span>
                                                        :<span style="color: {{$dangerColor[$key]}}">{{json_decode($device->deviceParameters()->orderBy('id','desc')->first()->parameters,true)[$parameter->code]}}  ({{$parameter->unit}})</span>
                                                    </div>
                                                @endforeach
                                                <span
                                                    style="color: #000000">{{\Carbon\Carbon::parse($device->deviceParameters()->orderBy('id','desc')->first()->time_of_read)->setTimezone('Asia/Damascus')->format('Y-d-m h:i a')}}  </span>
                                            @else
                                                @foreach($testPara as $key=>$parameter)
                                                    {{--                                                <h4>{{$key}}</h4>--}}
                                                    <div><p style=""></p>
                                                        {{--                                                    {{dd($dangerColor)}}--}}
                                                        <span
                                                            style="color: {{$dangerColor[$key]}}">{{$parameter->name}}</span>
                                                        :<span style="color: {{$dangerColor[$key]}}">{{json_decode($device->deviceParameters()->orderBy('id','desc')->first()->parameters,true)[$parameter->code]}}  ({{$parameter->unit}})</span>
                                                    </div>
                                                @endforeach
                                                <span
                                                    style="color: #000000">{{\Carbon\Carbon::parse($device->deviceParameters()->orderBy('id','desc')->first()->time_of_read)->setTimezone('Asia/Damascus')->format('Y-d-m h:i a')}}  </span>

                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('scripts')
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
