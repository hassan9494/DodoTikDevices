
<section class="box-fancy section-fullwidth text-light p-b-0">
    <div class="row" style="text-align: -webkit-center;">
        <div class="col-lg-12 col-xxl-12 order-1 order-xxl-1 mb-4">
            <div class="card card-custom mb-4">


                <div class="card-body pt-2" style="position: relative;">
                    <div id="chart-container"></div>
                </div>
            </div>
        </div>
    </div>
</section>


@push('scripts')
    <script src="https://fastly.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
    <script id="code">
        var dom = document.getElementById('chart-container');
        var myChart = echarts.init(dom, null, {
            renderer: 'canvas',
            useDirtyRect: false
        });
        var app = {};

        var option;

        option = {
            series: [
                {
                    type: 'gauge',
                    center: ['50%', '60%'],
                    startAngle: 200,
                    endAngle: -20,
                    min: 0,
                    max: 60,
                    splitNumber: 12,
                    itemStyle: {
                        color: '#FFAB91'
                    },
                    progress: {
                        show: true,
                        width: 30
                    },
                    pointer: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            width: 30
                        }
                    },
                    axisTick: {
                        distance: -45,
                        splitNumber: 5,
                        lineStyle: {
                            width: 2,
                            color: '#999'
                        }
                    },
                    splitLine: {
                        distance: -52,
                        length: 14,
                        lineStyle: {
                            width: 3,
                            color: '#999'
                        }
                    },
                    axisLabel: {
                        distance: -20,
                        color: '#999',
                        fontSize: 20
                    },
                    anchor: {
                        show: false
                    },
                    title: {
                        show: false
                    },
                    detail: {
                        valueAnimation: true,
                        width: '60%',
                        lineHeight: 40,
                        borderRadius: 8,
                        offsetCenter: [0, '-15%'],
                        fontSize: 60,
                        fontWeight: 'bolder',
                        formatter: '{value} °C',
                        color: 'auto'
                    },
                    data: [
                        {
                            value: 20
                        }
                    ]
                },
                {
                    type: 'gauge',
                    center: ['50%', '60%'],
                    startAngle: 200,
                    endAngle: -20,
                    min: 0,
                    max: 60,
                    itemStyle: {
                        color: '#FD7347'
                    },
                    progress: {
                        show: true,
                        width: 8
                    },
                    pointer: {
                        show: false
                    },
                    axisLine: {
                        show: false
                    },
                    axisTick: {
                        show: false
                    },
                    splitLine: {
                        show: false
                    },
                    axisLabel: {
                        show: false
                    },
                    detail: {
                        show: false
                    },
                    data: [
                        {
                            value: 20
                        }
                    ]
                }
            ]
        };
        setInterval(function () {
            const random = +(Math.random() * 60).toFixed(2);
            myChart.setOption({
                series: [
                    {
                        data: [
                            {
                                value: random
                            }
                        ]
                    },
                    {
                        data: [
                            {
                                value: random
                            }
                        ]
                    }
                ]
            });
        }, 5000);


        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        window.addEventListener('resize', myChart.resize);
    </script>
@endpush
