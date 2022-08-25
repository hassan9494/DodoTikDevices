
<section class="box-fancy section-fullwidth text-light p-b-0">
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">


                <div class="card-body pt-2" style="position: relative;">
                    <div id="chartdiv"></div>
                    <div class="spinner-border  text-success" role="status" id="spinner">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="resize-triggers">
                        <div class="expand-trigger">
                            <div style="width: 1291px; height: 399px;"></div>
                        </div>
                        <div class="contract-trigger"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('scripts')
    <script>
        jQuery.ajax({
            url: '/admin/devices/getGaugeWithBandsData/{{$device->id}}/',
            type: 'GET',
            success: function (gaugeWithBandsdata) {
                console.log(gaugeWithBandsdata[0])
                am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
                    var root = am5.Root.new("chartdiv");


// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
                    root.setThemes([
                        am5themes_Animated.new(root)
                    ]);


// Create chart
// https://www.amcharts.com/docs/v5/charts/radar-chart/
                    var chart = root.container.children.push(am5radar.RadarChart.new(root, {
                        panX: false,
                        panY: false,
                        startAngle: 160,
                        endAngle: 380
                    }));


// Create axis and its renderer
// https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Axes
                    var axisRenderer = am5radar.AxisRendererCircular.new(root, {
                        innerRadius: -40
                    });

                    axisRenderer.grid.template.setAll({
                        stroke: root.interfaceColors.get("background"),
                        visible: true,
                        strokeOpacity: 0.8
                    });

                    var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
                        maxDeviation: 0,
                        min: -15,
                        max: 60,
                        strictMinMax: true,
                        renderer: axisRenderer
                    }));


// Add clock hand
// https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Clock_hands
                    var axisDataItem = xAxis.makeDataItem({});

                    var clockHand = am5radar.ClockHand.new(root, {
                        pinRadius: am5.percent(20),
                        radius: am5.percent(100),
                        bottomWidth: 40
                    })

                    var bullet = axisDataItem.set("bullet", am5xy.AxisBullet.new(root, {
                        sprite: clockHand
                    }));

                    xAxis.createAxisRange(axisDataItem);

                    var label = chart.radarContainer.children.push(am5.Label.new(root, {
                        fill: am5.color(0xffffff),
                        centerX: am5.percent(50),
                        textAlign: "center",
                        centerY: am5.percent(50),
                        fontSize: "3em"
                    }));

                    axisDataItem.set("value", gaugeWithBandsdata[0]);
                    bullet.get("sprite").on("rotation", function () {
                        var value = axisDataItem.get("value");
                        var text = Math.round(axisDataItem.get("value")).toString();
                        var fill = am5.color(0x000000);
                        xAxis.axisRanges.each(function (axisRange) {
                            if (value >= axisRange.get("value") && value <= axisRange.get("endValue")) {
                                fill = axisRange.get("axisFill").get("fill");
                            }
                        })

                        label.set("text", Math.round(value).toString());

                        clockHand.pin.animate({ key: "fill", to: fill, duration: 500, easing: am5.ease.out(am5.ease.cubic) })
                        clockHand.hand.animate({ key: "fill", to: fill, duration: 500, easing: am5.ease.out(am5.ease.cubic) })
                    });

                    // setInterval(function () {
                    //     console.log(Math.random())
                    //     axisDataItem.animate({
                    //         key: "value",
                    //         to: Math.round(Math.random() * 140 - 40),
                    //         duration: 500,
                    //         easing: am5.ease.out(am5.ease.cubic)
                    //     });
                    // }, 2000)

                    chart.bulletsContainer.set("mask", undefined);


// Create axis ranges bands
// https://www.amcharts.com/docs/v5/charts/radar-chart/gauge-charts/#Bands
                    var bandsData = [{
                        title: "Very Low",
                        color: "#ee1f25",
                        lowScore: -15,
                        highScore: 0
                    }, {
                        title: "Low",
                        color: "#f04922",
                        lowScore: 0,
                        highScore: 15
                    }, {
                        title: "Medium",
                        color: "#fdae19",
                        lowScore: 15,
                        highScore: 30
                    }, {
                        title: "High",
                        color: "#f3eb0c",
                        lowScore: 30,
                        highScore: 45
                    }, {
                        title: "Very High",
                        color: "#b0d136",
                        lowScore: 45,
                        highScore: 60
                    }];

                    am5.array.each(bandsData, function (data) {
                        var axisRange = xAxis.createAxisRange(xAxis.makeDataItem({}));
                        // console.log(data)
                        axisRange.setAll({
                            value: data.lowScore,
                            endValue: data.highScore
                        });

                        axisRange.get("axisFill").setAll({
                            visible: true,
                            fill: am5.color(data.color),
                            fillOpacity: 0.8
                        });

                        axisRange.get("label").setAll({
                            text: data.title,
                            inside: true,
                            radius: 15,
                            fontSize: "0.9em",
                            fill: root.interfaceColors.get("background")
                        });
                    });


// Make stuff animate on load
                    chart.appear(1000, 100);

                }); // end am5.ready()
            },
            error: function (xhr, b, c) {
                console.log("xhr=" + xhr + " b=" + b + " c=" + c);
            }
        });

    </script>
@endpush
