<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Election Progress Page</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    @include ('headerhome')
        <div class="contentcontainter">
            <canvas id="chart" ></canvas>
        </div>

<script>
    var context = document.getElementById('chart').getContext('2d');
    var chart = new Chart(context, {
        type: 'bar',
        data: {
                labels: {!!json_encode($chart->labels)!!},
                datasets: [
                        {
                            label: 'Vote Count',
                            data: {!!json_encode($chart->dataset)!!},
                            backgroundColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                ],
                                borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                ],
                                borderWidth: 1,
                        },
                    ]
            },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value) {if (value % 1 === 0) {return value;}}
                    },
                    scaleLabel: {
                        display: false
                    }
                }]
            },
            legend: {
                labels: {
                    // This more specific font property overrides the global property
                    fontColor: '#122C4B',
                    fontFamily: "'Muli', sans-serif",
                    padding: 25,
                    boxWidth: 25,
                    fontSize: 14,
                }
            },
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 0,
                    bottom: 10
                }
            }
        }
    });
</script>
</body>
</html>





