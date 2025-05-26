@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Tableau de bord Analyste</h2>

    <div class="row mt-4">
        <div class="col-md-12">
            <h4 class="mb-3">Rapports récents</h4>
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Rapport 1
                    <span class="badge bg-secondary rounded-pill">01/10/2023</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Rapport 2
                    <span class="badge bg-secondary rounded-pill">02/10/2023</span>
                </a>
                <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    Rapport 3
                    <span class="badge bg-secondary rounded-pill">03/10/2023</span>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h4>Répartition des alertes</h4>
        <svg id="statusChart" width="1400" height="400"></svg>
    </div>
</div>
@endsection

<script src="https://d3js.org/d3.v7.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const data = [
            { month: 'January', attacks: 30 },
            { month: 'February', attacks: 20 },
            { month: 'March', attacks: 25 },
            { month: 'April', attacks: 35 },
            { month: 'May', attacks: 40 },
            { month: 'June', attacks: 30 }
        ];

        const svg = d3.select("#statusChart"),
            width = +svg.attr("width"),
            height = +svg.attr("height"),
            margin = { top: 20, right: 20, bottom: 30, left: 50 };

        const x = d3.scaleBand()
            .domain(data.map(d => d.month))
            .range([margin.left, width - margin.right])
            .padding(0.1);

        const y = d3.scaleLinear()
            .domain([0, d3.max(data, d => d.attacks)]).nice()
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

        svg.selectAll(".bar")
            .data(data)
            .enter().append("rect")
            .attr("class", "bar")
            .attr("x", d => x(d.month))
            .attr("y", d => y(d.attacks))
            .attr("width", x.bandwidth())
            .attr("height", d => y(0) - y(d.attacks))
            .attr("fill", "#007bff");
    });
</script>
