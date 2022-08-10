
<section class="box-fancy section-fullwidth text-light p-b-0">
    {{--        <div class="row">--}}
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">


                <div class="card-body pt-2" style="position: relative;">
                    <div id="allSampleContent" class="p-4 w-full">


                        <div id="sample">
                            <div id="myDiagramDiv" style="border: 1px solid black; width: 100%; height: 350px; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);">
                                <canvas tabindex="0" width="1054" height="348" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 1054px; height: 348px;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
                                <div style="position: absolute; overflow: auto; width: 1054px; height: 348px; z-index: 1;">
                                    <div style="position: absolute; width: 1px; height: 1px;">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--        </div>--}}
</section>


@push('scripts')
    <script id="code">
        function init() {

            // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
            // For details, see https://gojs.net/latest/intro/buildingObjects.html
            const $ = go.GraphObject.make;

            myDiagram = $(go.Diagram, "myDiagramDiv");

            myDiagram.nodeTemplate =
                $(go.Node, "Auto",
                    $(go.Shape, "Circle",
                        { stroke: "orange", strokeWidth: 5, spot1: go.Spot.TopLeft, spot2: go.Spot.BottomRight },
                        new go.Binding("stroke", "color")),
                    $(go.Panel, "Spot",
                        $(go.Panel, "Graduated",
                            {
                                name: "SCALE", margin: 14,
                                graduatedTickUnit: 2.5,  // tick marks at each multiple of 2.5
                                graduatedMax: 100,  // this is actually the default value
                                stretch: go.GraphObject.None  // needed to avoid unnecessary re-measuring!!!
                            },
                            new go.Binding("graduatedMax", "max"),  // controls the range of the gauge
                            // the main path of the graduated panel, an arc starting at 135 degrees and sweeping for 270 degrees
                            $(go.Shape, { name: "SHAPE", geometryString: "M-70.7107 70.7107 B135 270 0 0 100 100 M0 100", stroke: "white", strokeWidth: 4 }),
                            // three differently sized tick marks
                            $(go.Shape, { geometryString: "M0 0 V10", stroke: "white", strokeWidth: 1.5 }),
                            $(go.Shape, { geometryString: "M0 0 V12", stroke: "white", strokeWidth: 2.5, interval: 2 }),
                            $(go.Shape, { geometryString: "M0 0 V15", stroke: "white", strokeWidth: 3.5, interval: 4 }),
                            $(go.TextBlock,
                                { // each tick label
                                    interval: 4,
                                    alignmentFocus: go.Spot.Center,
                                    font: "bold italic 14pt sans-serif", stroke: "white",
                                    segmentOffset: new go.Point(0, 30)
                                })
                        ),
                        $(go.TextBlock,
                            { alignment: new go.Spot(0.5, 0.9), stroke: "orange", font: "bold italic 14pt sans-serif" },
                            new go.Binding("text", "key"),
                            new go.Binding("stroke", "color")),
                        $(go.Shape, { fill: "red", strokeWidth: 0, geometryString: "F1 M-6 0 L0 -6 100 0 0 6z x M-100 0" },
                            new go.Binding("angle", "value", convertValueToAngle)),
                        $(go.Shape, "Circle", { width: 2, height: 2, fill: "#444" })
                    )
                );

            // this determines the angle of the needle, based on the data.value argument
            function convertValueToAngle(v, shape) {
                var scale = shape.part.findObject("SCALE");
                var p = scale.graduatedPointForValue(v);
                var shape = shape.part.findObject("SHAPE");
                var c = shape.actualBounds.center;
                return c.directionPoint(p);
            }

            myDiagram.model = new go.GraphLinksModel([
                { key: "Min", value: 35 },
                { key: "Max", color: "green", max: 140, value: 70 }
            ], [
                { from: "Min", to: "Max" }
            ]);

            loop();
        }

        // change each gauge's value several times a second
        function loop() {
            setTimeout(() => {
                myDiagram.startTransaction();
                myDiagram.nodes.each(node => {
                    var scale = node.findObject("SCALE");
                    if (scale === null || scale.type !== go.Panel.Graduated) return;
                    // keep the new value within the range of the graduated panel
                    var min = scale.graduatedMin;
                    var max = scale.graduatedMax;
                    var v = node.data.value;
                    if (v === undefined) v = Math.floor((max - min) / 2);  // default to middle value
                    if (v < min) v++;
                    else if (v > max) v--;
                    else v += (Math.random() < 0.5) ? -0.5 : 0.5;  // random walk
                    myDiagram.model.setDataProperty(node.data, "value", v);
                });
                myDiagram.commitTransaction("modified Graduated Panel");
                loop();
            }, 1000 / 6);
        }
        window.addEventListener('DOMContentLoaded', init);
    </script>
@endpush
