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
    @include ('headerdashboard')
        <div class="main">
            <button class="btn btn-primary"><a style="list-style: none" href="{{ url()->previous() }}">Back to District List Page</a></button>
            <div class="title">
                <p>District Election Results Analysis</p>
            </div>
            <div class="chartcontainer">
                <canvas id="chart" ></canvas>
            </div>
        </div>
        

<script>
    var context = document.getElementById('chart').getContext('2d');
    var chart = new Chart(context, {
        type: 'pie',
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
                                    'rgba(200, 164, 140, 0.5)',
                                    'rgba(218, 219, 27, 0.9)',
                                    'rgba(30, 32, 98, 0.5)',
                                    'rgba(200, 164, 140, 0.5)',
                                    'rgba(50, 174, 150, 1)',
                                ],
                                borderColor: [
                                    'rgba(255,99,132,1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(200, 164, 140, 0.5)',
                                    'rgba(218, 219, 27, 0.9)',
                                    'rgba(30, 32, 98, 0.5)',
                                    'rgba(200, 164, 140, 0.5)',
                                    'rgba(50, 174, 150, 1)',
                                ],
                                borderWidth: 1,
                        },
                    ]
            },
        options: {
            responsive: true,
            maintainAspectRatio:false,
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





