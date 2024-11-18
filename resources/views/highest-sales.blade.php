@php use Carbon\Carbon; @endphp

    <!DOCTYPE html>
<html>
<head>
    <title>Highest Sales Theater</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@1.0.0"></script>
    <style>
        #chartContainer {
            height: 500px;
            max-height: 500px;
            position: relative;
            margin: auto;
        }
    </style>
</head>
<body>
<h1>Find Theater with Highest Sales</h1>

<form method="POST" action="{{ route('highest-sales.show') }}">
    @csrf
    <label for="sale_date">Select Date:</label>
    <input type="date" id="sale_date" name="sale_date" required value="{{$sale_date}}">
    <button type="submit">Submit</button>
</form>

@if(isset($theater))
    <h2>Results for {{ $sale_date }}:</h2>
    <p>Theater: {{ $theater }}</p>
    <p>Tickets Sold: {{ $tickets_sold }}</p>
@elseif(isset($message))
    <p>{{ $message }}</p>
@endif

@if ($errors && $errors->any())
    <div>
        <strong>Error:</strong> {{ $errors->first('sale_date') }}
    </div>
@endif

@if(isset($dates) && count($dates) > 0)
    <h2>Monthly Sales Chart for {{ $theater }} {{ Carbon::parse($sale_date)->format('F Y') }}</h2>
    <div id="chartContainer">
        <canvas id="salesChart"></canvas>
    </div>

    <script>
        const labels = {!! json_encode($dates) !!};
        const data = {!! json_encode($sales) !!};
        const selectedDate = "{{ $sale_date }}";
        const backgroundColors = [];
        const borderColors = [];
        const defaultBackgroundColor = 'rgba(54, 162, 235, 0.6)';
        const defaultBorderColor = 'rgba(54, 162, 235, 1)';
        const highlightBackgroundColor = 'rgba(255, 99, 132, 0.6)';
        const highlightBorderColor = 'rgba(255, 99, 132, 1)';

        labels.forEach((label) => {
            if (label === selectedDate) {
                backgroundColors.push(highlightBackgroundColor);
                borderColors.push(highlightBorderColor);
            } else {
                backgroundColors.push(defaultBackgroundColor);
                borderColors.push(defaultBorderColor);
            }
        });
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tickets Sold',
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1,
                    fill: false,
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            displayFormats: {
                                day: 'MMM D'
                            },
                            tooltipFormat: 'MMM D',
                        },
                        title: {
                            display: true,
                            text: 'Date'
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Tickets Sold'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    </script>
@endif
</body>
</html>
