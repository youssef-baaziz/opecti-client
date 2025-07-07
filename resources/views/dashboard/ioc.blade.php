@extends('layouts.app')
@section('content')
<div class="p-6 text-gray-900">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Tableau de bord des Indicateurs de Compromission (IOCs)</h3>

    <div class="mb-4">
        <label for="ioc_filter_days" class="block text-sm font-medium text-gray-700">Période :</label>
        <select id="ioc_filter_days" class="mt-1 block w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option value="7">7 derniers jours</option>
            <option value="30">30 derniers jours</option>
            <option value="90">90 derniers jours</option>
            <option value="0">Toutes les données</option>
        </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <h4 class="text-md font-semibold text-gray-800 mb-2">Répartition des types d'IOC</h4>
            <div id="ioc-type-chart" class="w-full h-80"></div>
        </div>
        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
            <h4 class="text-md font-semibold text-gray-800 mb-2">IOCs par jour (7 derniers jours)</h4>
            <div id="ioc-daily-chart" class="w-full h-80"></div>
        </div>
    </div>

    <div class="mt-6 bg-gray-50 p-4 rounded-lg shadow-sm">
        <h4 class="text-md font-semibold text-gray-800 mb-2">Liste des IOCs (récents)</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valeur</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détecté le</th>
                    </tr>
                </thead>
                <tbody id="ioc-list-body" class="bg-white divide-y divide-gray-200">
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://d3js.org/d3.v7.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const iocFilterDays = document.getElementById('ioc_filter_days');

            function fetchIocs(days) {
                const url = `/dashboard/iocs?last_days=${days}`;

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Données IOCs chargées:', data);
                        updateVisualizations(data);
                    })
                    .catch(error => {
                        console.error('Erreur lors du chargement des données IOCs:', error);
                        // Afficher un message d'erreur à l'utilisateur
                    });
            }

            function updateVisualizations(iocs) {
                // Nettoyer les graphiques existants
                d3.select("#ioc-type-chart svg").remove();
                d3.select("#ioc-daily-chart svg").remove();
                d3.select("#ioc-list-body").html(""); // Nettoyer la table

                // 1. Visualisation de la répartition des types d'IOC (Graphique en Barres)
                createIocTypeChart(iocs);

                // 2. Visualisation des IOCs par jour (Graphique en Aires/Barres)
                createIocDailyChart(iocs);

                // 3. Affichage de la liste des IOCs
                displayIocList(iocs.slice(0, 50)); // Afficher les 50 IOCs les plus récents
            }

            function createIocTypeChart(iocs) {
                const typeCounts = d3.rollup(iocs, v => v.length, d => d.type);
                const data = Array.from(typeCounts, ([type, count]) => ({ type, count }));
                data.sort((a, b) => b.count - a.count); // Trier par nombre décroissant

                const margin = { top: 20, right: 30, bottom: 60, left: 50 };
                const width = document.getElementById('ioc-type-chart').clientWidth - margin.left - margin.right;
                const height = document.getElementById('ioc-type-chart').clientHeight - margin.top - margin.bottom;

                const svg = d3.select("#ioc-type-chart")
                    .append("svg")
                    .attr("width", width + margin.left + margin.right)
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g")
                    .attr("transform", `translate(${margin.left},${margin.top})`);

                const x = d3.scaleBand()
                    .range([0, width])
                    .padding(0.1)
                    .domain(data.map(d => d.type));

                const y = d3.scaleLinear()
                    .range([height, 0])
                    .domain([0, d3.max(data, d => d.count)]);

                svg.append("g")
                    .attr("transform", `translate(0,${height})`)
                    .call(d3.axisBottom(x))
                    .selectAll("text")
                    .attr("transform", "rotate(-45)")
                    .style("text-anchor", "end");

                svg.append("g")
                    .call(d3.axisLeft(y));

                svg.selectAll(".bar")
                    .data(data)
                    .enter().append("rect")
                    .attr("class", "bar")
                    .attr("x", d => x(d.type))
                    .attr("y", d => y(d.count))
                    .attr("width", x.bandwidth())
                    .attr("height", d => height - y(d.count))
                    .attr("fill", "steelblue");

                // Ajout des tooltips basiques
                svg.selectAll(".bar")
                    .on("mouseover", function(event, d) {
                        d3.select(this).attr("fill", "orange");
                        svg.append("text")
                            .attr("class", "tooltip-text")
                            .attr("x", x(d.type) + x.bandwidth() / 2)
                            .attr("y", y(d.count) - 5)
                            .attr("text-anchor", "middle")
                            .text(d.count);
                    })
                    .on("mouseout", function() {
                        d3.select(this).attr("fill", "steelblue");
                        svg.selectAll(".tooltip-text").remove();
                    });
            }

            function createIocDailyChart(iocs) {
                // Agrégation par jour
                const dailyCounts = d3.rollup(iocs, v => v.length, d => {
                    // Assurez-vous que detected_at est bien une date
                    return new Date(d.detected_at).toISOString().split('T')[0];
                });
                const data = Array.from(dailyCounts, ([date, count]) => ({ date: new Date(date), count }));
                data.sort((a, b) => a.date - b.date); // Trier par date

                // Remplir les jours manquants pour une série continue sur les 7 derniers jours
                const today = new Date();
                const oneWeekAgo = new Date();
                oneWeekAgo.setDate(today.getDate() - 6); // Pour avoir 7 jours (aujourd'hui + 6 précédents)

                const fullData = [];
                for (let d = new Date(oneWeekAgo); d <= today; d.setDate(d.getDate() + 1)) {
                    const dateString = d.toISOString().split('T')[0];
                    const existingData = data.find(item => item.date.toISOString().split('T')[0] === dateString);
                    fullData.push({
                        date: new Date(dateString), // Assurer que c'est un objet Date
                        count: existingData ? existingData.count : 0
                    });
                }

                const margin = { top: 20, right: 30, bottom: 40, left: 50 };
                const width = document.getElementById('ioc-daily-chart').clientWidth - margin.left - margin.right;
                const height = document.getElementById('ioc-daily-chart').clientHeight - margin.top - margin.bottom;

                const svg = d3.select("#ioc-daily-chart")
                    .append("svg")
                    .attr("width", width + margin.left + margin.right)
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g")
                    .attr("transform", `translate(${margin.left},${margin.top})`);

                const x = d3.scaleTime()
                    .domain(d3.extent(fullData, d => d.date))
                    .range([0, width]);

                const y = d3.scaleLinear()
                    .domain([0, d3.max(fullData, d => d.count) || 10]) // Assurer une hauteur minimale
                    .range([height, 0]);

                // Axe X formaté pour les dates
                svg.append("g")
                    .attr("transform", `translate(0,${height})`)
                    .call(d3.axisBottom(x).ticks(d3.timeDay.every(1)).tickFormat(d3.timeFormat("%b %d"))); // Format Jours/Mois

                svg.append("g")
                    .call(d3.axisLeft(y).ticks(Math.min(5, d3.max(fullData, d => d.count) || 5)).tickFormat(d3.format("d"))); // Nombres entiers

                // Graphique en barres pour la clarté visuelle quotidienne
                svg.selectAll(".bar")
                    .data(fullData)
                    .enter().append("rect")
                    .attr("class", "bar")
                    .attr("x", d => x(d.date) - (width / fullData.length / 2)) // Centrer les barres
                    .attr("width", width / fullData.length * 0.8) // Largeur des barres
                    .attr("y", d => y(d.count))
                    .attr("height", d => height - y(d.count))
                    .attr("fill", "teal");

                 // Ajout des tooltips basiques pour le graphique journalier
                 svg.selectAll(".bar")
                    .on("mouseover", function(event, d) {
                        d3.select(this).attr("fill", "orange");
                        svg.append("text")
                            .attr("class", "tooltip-text")
                            .attr("x", x(d.date))
                            .attr("y", y(d.count) - 5)
                            .attr("text-anchor", "middle")
                            .text(`${d3.timeFormat("%b %d")(d.date)}: ${d.count}`);
                    })
                    .on("mouseout", function() {
                        d3.select(this).attr("fill", "teal");
                        svg.selectAll(".tooltip-text").remove();
                    });
            }

            function displayIocList(iocs) {
                const tbody = d3.select("#ioc-list-body");

                const rows = tbody.selectAll("tr")
                    .data(iocs)
                    .enter()
                    .append("tr");

                rows.append("td")
                    .attr("class", "px-6 py-4 whitespace-nowrap text-sm text-gray-900")
                    .text(d => d.type);

                rows.append("td")
                    .attr("class", "px-6 py-4 whitespace-nowrap text-sm text-gray-900")
                    .text(d => d.value);

                rows.append("td")
                    .attr("class", "px-6 py-4 whitespace-nowrap text-sm text-gray-900")
                    .text(d => d.source || 'N/A');

                rows.append("td")
                    .attr("class", "px-6 py-4 whitespace-nowrap text-sm text-gray-500")
                    .text(d => new Date(d.detected_at).toLocaleString());
            }

            // Gérer le changement du filtre de période
            iocFilterDays.addEventListener('change', function() {
                fetchIocs(this.value);
            });

            // Charger les données initiales au chargement de la page (7 derniers jours par défaut)
            fetchIocs(iocFilterDays.value);
        });
    </script>
@endpush
@endsection