@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-4">
        @php
            $cards = [
                ['title' => 'Clients', 'count' => 75, 'bg' => 'bg-primary-emphasis'],
                ['title' => 'Alertes (Total)', 'count' => 250, 'bg' => 'bg-warning-emphasis'],
                ['title' => 'Actives', 'count' => 125, 'bg' => 'bg-success-emphasis'],
                ['title' => 'Inactives', 'count' => 75, 'bg' => 'bg-danger-emphasis']
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-md-3">
            <div class="card text-dark {{ $card['bg'] }} mb-3 shadow-sm">
                <div class="card-body d-flex flex-column align-items-center">
                    <h5 class="card-title mb-2">{{ $card['title'] }}</h5>
                    <p class="card-text display-4">{{ $card['count'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        @php
            $charts = [
                ['id' => 'chart1', 'title' => '📊 Area Chart - Threats Over Time'],
                ['id' => 'chart2', 'title' => '📊 Line Chart - Attack Vectors']
            ];
        @endphp

        @foreach($charts as $chart)
        <div class="col-md-6">
            <h5 class="text-center">{{ $chart['title'] }}</h5>
            <svg id="{{ $chart['id'] }}" width="700" height="300"></svg>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <h5 class="text-center">📊 Bar Chart - Incident Response Times</h5>
            <svg id="chart4" width="1400" height="300"></svg>
        </div>
    </div>

    <canvas id="alertsChart" height="100"></canvas>
</div>
@endsection

<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const charts = [
            { id: '#chart1', type: 'area', title: 'Threats Over Time' },
            { id: '#chart2', type: 'line', title: 'Attack Vectors' },
            { id: '#chart4', type: 'bar', title: 'Incident Response Times' }
        ];

        charts.forEach(chart => {
            let data;
            switch (chart.type) {
                case 'candlestick':
                    data = generateCandlestickData();
                    break;
                case 'stacked':
                    data = generateStackedData();
                    break;
                default:
                    data = generateDefaultData();
            }

            const svg = d3.select(chart.id),
                width = +svg.attr("width"),
                height = +svg.attr("height"),
                margin = { top: 40, right: 20, bottom: 50, left: 60 };

            renderChart(svg, chart.type, data, width, height, margin);
        });

        function generateCandlestickData() {
            return Array.from({ length: 30 }, (_, i) => {
                const date = new Date();
                date.setDate(date.getDate() - (29 - i));
                const open = Math.random() * 100 + 200;
                const close = open + (Math.random() * 20 - 10);
                const high = Math.max(open, close) + Math.random() * 10;
                const low = Math.min(open, close) - Math.random() * 10;
                return { date, open, close, high, low };
            });
        }

        function generateStackedData() {
            const months = Array.from({ length: 12 }, (_, i) =>
                new Date(0, i).toLocaleString('default', { month: 'long' })
            );
            return months.map(month => ({
                month,
                A: Math.floor(Math.random() * (400 - 100 + 1)) + 100,
                B: Math.floor(Math.random() * (400 - 100 + 1)) + 100,
                C: Math.floor(Math.random() * (400 - 100 + 1)) + 100
            }));
        }

        function generateDefaultData() {
            return Array.from({ length: 12 }, (_, i) => ({
                month: new Date(0, i).toLocaleString('default', { month: 'long' }),
                total: Math.floor(Math.random() * 1000)
            }));
        }

        function renderChart(svg, type, data, width, height, margin) {
            svg.selectAll("*").remove();
            const x = d3.scaleBand()
                .domain(data.map(d => d.month || d.date))
                .range([margin.left, width - margin.right])
                .padding(0.1);

            const y = d3.scaleLinear()
                .domain([0, d3.max(data, d => d.total || d.high)]).nice()
                .range([height - margin.bottom, margin.top]);

            svg.append("g")
                .attr("transform", `translate(0,${height - margin.bottom})`)
                .call(d3.axisBottom(x))
                .selectAll("text")
                .attr("transform", "rotate(-40)")
                .style("text-anchor", "end");

            svg.append("g")
                .attr("transform", `translate(${margin.left},0)`)
                .call(d3.axisLeft(y));

            if (type === 'bar') {
                svg.selectAll(".bar")
                    .data(data)
                    .enter().append("rect")
                    .attr("class", "bar")
                    .attr("x", d => x(d.month))
                    .attr("y", d => y(d.total))
                    .attr("width", x.bandwidth())
                    .attr("height", d => y(0) - y(d.total));
            } else if (type === 'line') {
                const line = d3.line()
                    .x(d => x(d.month) + x.bandwidth() / 2)
                    .y(d => y(d.total));

                svg.append("path")
                    .datum(data)
                    .attr("fill", "none")
                    .attr("stroke", "steelblue")
                    .attr("stroke-width", 1.5)
                    .attr("d", line);
            } else if (type === 'area') {
                const area = d3.area()
                    .x(d => x(d.month) + x.bandwidth() / 2)
                    .y0(y(0))
                    .y1(d => y(d.total));

                svg.append("path")
                    .datum(data)
                    .attr("fill", "steelblue")
                    .attr("d", area);
            }
        }
    });
</script>