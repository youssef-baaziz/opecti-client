{{-- Start of Selection --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tableau de bord Client - Cyber Security Insights</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mt-4">
        <div class="col-md-6">
            <h5 style="text-align: center;">📊 Area Chart - Threats Over Time</h5>
                <svg id="chart1" width="700" height="300"></svg>
        </div>
        <div class="col-md-6">
            <h5 style="text-align: center;">📊 Line Chart - Attack Vectors</h5>
                <svg id="chart2" width="700" height="300"></svg>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <h5 style="text-align: center;">📊 Histogram - Vulnerability Trends</h5>
                <svg id="chart3" width="700" height="300"></svg>
        </div>
        <div class="col-md-6">
            <h5 style="text-align: center;">📊 Bar Chart - Incident Response Times</h5>
                <svg id="chart4" width="700" height="300"></svg>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <h5 style="text-align: center;">📊 Stacked-to-Grouped Bars - Security Breaches</h5>
                <svg id="chart5" width="700" height="300"></svg>
        </div>
        <div class="col-md-6">
            <h5 style="text-align: center;">📊 Candlestick Chart - User Awareness Levels</h5>
                <svg id="chart6" width="700" height="300"></svg>
        </div>
    </div>
</div>
@endsection
<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const charts = [{
                id: '#chart1',
                type: 'area',
                title: 'Threats Over Time'
            },
            {
                id: '#chart2',
                type: 'line',
                title: 'Attack Vectors'
            },
            {
                id: '#chart3',
                type: 'histogram',
                title: 'Vulnerability Trends'
            },
            {
                id: '#chart4',
                type: 'bar',
                title: 'Incident Response Times'
            },
            {
                id: '#chart5',
                type: 'stacked',
                title: 'Security Breaches'
            },
            {
                id: '#chart6',
                type: 'candlestick',
                title: 'User Awareness Levels'
            }
        ];

        charts.forEach(chart => {
            let data;
            if (chart.type === 'candlestick') {
                data = Array.from({ length: 30 }, (_, i) => {
                    const date = new Date();
                    date.setDate(date.getDate() - (29 - i));
                    const open = Math.random() * 100 + 200;
                    const close = open + (Math.random() * 20 - 10);
                    const high = Math.max(open, close) + Math.random() * 10;
                    const low = Math.min(open, close) - Math.random() * 10;

                    return {
                        date,
                        open,
                        close,
                        high,
                        low
                    };
                });
            } else if (chart.type === 'stacked') {
                const months = Array.from({ length: 12 }, (_, i) =>
                    new Date(0, i).toLocaleString('default', { month: 'long' })
                );

                data = months.map(month => ({
                    month,
                    A: Math.floor(Math.random() * (400 - 100 + 1)) + 100,
                    B: Math.floor(Math.random() * (400 - 100 + 1)) + 100,
                    C: Math.floor(Math.random() * (400 - 100 + 1)) + 100
                }));
            } else {
                data = Array.from({ length: 12 }, (_, i) => ({
                    month: new Date(0, i).toLocaleString('default', { month: 'long' }),
                    total: Math.floor(Math.random() * 1000)
                }));
            }

            const svg = d3.select(chart.id),
                width = +svg.attr("width"),
                height = +svg.attr("height"),
                margin = { top: 40, right: 20, bottom: 50, left: 60 };

            if (chart.type === 'candlestick') {
                const x = d3.scaleBand()
                    .domain(data.map(d => d.date))
                    .range([0, width])
                    .padding(0.3);

                const y = d3.scaleLinear()
                    .domain([d3.min(data, d => d.low), d3.max(data, d => d.high)])
                    .nice()
                    .range([height, 0]);

                const g = svg.append("g").attr("transform", `translate(${margin.left},${margin.top})`);

                g.append("g")
                    .attr("transform", `translate(0,${height})`)
                    .call(d3.axisBottom(x).tickFormat(d3.timeFormat("%b %d")).tickValues(x.domain().filter((_, i) => i % 3 === 0)));

                g.append("g")
                    .call(d3.axisLeft(y));

                g.selectAll(".candle")
                    .data(data)
                    .enter().append("line")
                    .attr("class", "candle")
                    .attr("x1", d => x(d.date) + x.bandwidth() / 2)
                    .attr("x2", d => x(d.date) + x.bandwidth() / 2)
                    .attr("y1", d => y(d.high))
                    .attr("y2", d => y(d.low))
                    .attr("stroke", d => d.close > d.open ? "#4CAF50" : "#F44336");

                g.selectAll(".rect")
                    .data(data)
                    .enter().append("rect")
                    .attr("x", d => x(d.date))
                    .attr("y", d => y(Math.max(d.open, d.close)))
                    .attr("width", x.bandwidth())
                    .attr("height", d => Math.max(1, Math.abs(y(d.open) - y(d.close))))
                    .attr("class", d => d.close > d.open ? "up" : "down");
            } else if (chart.type === 'stacked') {
                const keys = ["A", "B", "C"];
                const x0 = d3.scaleBand()
                    .domain(data.map(d => d.month))
                    .rangeRound([margin.left, width - margin.right])
                    .paddingInner(0.1);

                const x1 = d3.scaleBand()
                    .domain(keys)
                    .rangeRound([0, x0.bandwidth()])
                    .padding(0.05);

                const y = d3.scaleLinear().rangeRound([height - margin.bottom, margin.top]);

                const color = d3.scaleOrdinal()
                    .domain(keys)
                    .range(["#6b486b", "#ff8c00", "#a05d56"]);

                const stack = d3.stack().keys(keys);
                svg.selectAll("*").remove();
                const stackedData = stack(data);
                y.domain([0, d3.max(data, d => keys.reduce((a, c) => a + d[c], 0))]);

                svg.append("g")
                    .selectAll("g")
                    .data(stackedData)
                    .join("g")
                    .attr("fill", d => color(d.key))
                    .selectAll("rect")
                    .data(d => d)
                    .join("rect")
                    .attr("x", (d, i) => x0(data[i].month))
                    .attr("y", d => y(d[1]))
                    .attr("height", d => y(d[0]) - y(d[1]))
                    .attr("width", x0.bandwidth())
                    .attr("class", "bar");

                svg.append("g")
                    .attr("transform", `translate(0,${height - margin.bottom})`)
                    .call(d3.axisBottom(x0))
                    .selectAll("text")
                    .attr("transform", "rotate(-40)")
                    .style("text-anchor", "end");

                svg.append("g")
                    .attr("transform", `translate(${margin.left},0)`)
                    .call(d3.axisLeft(y));
            } else {
                const x = d3.scaleBand()
                    .domain(data.map(d => d.month))
                    .range([margin.left, width - margin.right])
                    .padding(0.1);

                const y = d3.scaleLinear()
                    .domain([0, d3.max(data, d => d.total)]).nice()
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

                if (chart.type === 'bar') {
                    svg.selectAll(".bar")
                        .data(data)
                        .enter().append("rect")
                        .attr("class", "bar")
                        .attr("x", d => x(d.month))
                        .attr("y", d => y(d.total))
                        .attr("width", x.bandwidth())
                        .attr("height", d => y(0) - y(d.total));
                } else if (chart.type === 'line') {
                    const line = d3.line()
                        .x(d => x(d.month) + x.bandwidth() / 2)
                        .y(d => y(d.total));

                    svg.append("path")
                        .datum(data)
                        .attr("fill", "none")
                        .attr("stroke", "steelblue")
                        .attr("stroke-width", 1.5)
                        .attr("d", line);
                } else if (chart.type === 'area') {
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
    });
</script>
{{-- End of Selection --}}