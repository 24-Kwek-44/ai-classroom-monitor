<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Live Session Insights</h1>
            <p class="text-gray-500 mt-1">Real-time analysis of the current session. Updates automatically.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Video and Live Chart -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Live Video Stream Player -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Live Session Feed (from AI Streamer)</h3>
                    <div class="aspect-video bg-gray-900 rounded-lg">
                        <!-- This <img> tag displays the live stream from your Python Flask server -->
                        <img src="http://127.0.0.1:5001/video_feed" class="w-full h-full rounded-lg object-contain" alt="Live AI Video Feed">
                    </div>
                </div>

                <!-- Live Attention Timeline -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold">Live Attention Timeline</h3>
                    <div class="relative h-80 mt-4">
                        <!-- This canvas is for our live chart -->
                        <canvas id="liveTimelineChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Column: Live Stats and Highlights -->
            <div class="space-y-6">
                <!-- Live Session Statistics -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Live Session Statistics</h3>
                    <div id="stats-container" class="space-y-4 text-sm">
                        <div class="flex justify-between items-center"><span class="text-gray-600">Current Attention</span><span id="stat-current" class="font-semibold text-gray-800">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Lowest Point</span><span id="stat-lowest" class="font-semibold text-red-500">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Highest Point</span><span id="stat-highest" class="font-semibold text-green-500">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Session Time</span><span id="stat-duration" class="font-semibold text-gray-800">--s</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Students Detected</span><span id="stat-detected" class="font-semibold text-gray-800">--</span></div>
                    </div>
                </div>

                <!-- Live Session Highlights -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-2">Live Session Highlights</h3>
                    <p class="text-sm text-gray-500 mb-4">Key moments from the session.</p>
                    <div id="highlights-container" class="space-y-3 max-h-60 overflow-y-auto">
                        <p id="highlights-placeholder" class="text-xs text-gray-400">Waiting for key events...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- STATE VARIABLES ---
            let liveChart;
            let lowestPoint = 100;
            let highestPoint = 0;
            let addedHighlights = {};

            // --- GET HTML ELEMENTS ---
            const highlightsContainer = document.getElementById('highlights-container');
            const highlightsPlaceholder = document.getElementById('highlights-placeholder');

            // --- 1. INITIALIZE THE CHART ---
            function initializeChart() {
                const ctx = document.getElementById('liveTimelineChart');
                // If the canvas doesn't exist on the page, stop the script
                if (!ctx) {
                    console.error("Chart canvas element not found!");
                    return;
                }

                liveChart = new Chart(ctx, {
                    type: 'line',
                    data: { labels: [], datasets: [{ label: 'Attention %', data: [], borderColor: '#1DE9B6', tension: 0.1, backgroundColor: 'rgba(29, 233, 182, 0.1)', fill: true }] },
                    options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, max: 100, ticks: { callback: (v) => `${v}%` } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
                });
            }

            // --- 2. FUNCTION TO UPDATE DASHBOARD ---
            async function updateDashboard() {
                try {
                    const response = await fetch('/api/get-status');
                    const data = await response.json();

                    if (!data || data.timestamp === undefined) return;

                    // Update Stats
                    document.getElementById('stat-current').textContent = `${data.attentiveness_percentage}%`;
                    document.getElementById('stat-duration').textContent = `${data.timestamp}s`;
                    document.getElementById('stat-detected').textContent = `${data.total_detected}`;
                    if (data.total_detected > 0) {
                        if (data.attentiveness_percentage < lowestPoint) lowestPoint = data.attentiveness_percentage;
                        if (data.attentiveness_percentage > highestPoint) highestPoint = data.attentiveness_percentage;
                    }
                    document.getElementById('stat-lowest').textContent = `${lowestPoint}%`;
                    document.getElementById('stat-highest').textContent = `${highestPoint}%`;

                    // Update Chart - check if liveChart exists before using it
                    if (liveChart) {
                        const lastLabel = liveChart.data.labels[liveChart.data.labels.length - 1];
                        if (lastLabel !== `${data.timestamp}s`) {
                            liveChart.data.labels.push(`${data.timestamp}s`);
                            liveChart.data.datasets[0].data.push(data.attentiveness_percentage);
                            if (liveChart.data.labels.length > 20) {
                                liveChart.data.labels.shift();
                                liveChart.data.datasets[0].data.shift();
                            }
                            liveChart.update('none');
                        }
                    }
                    
                    // Update Highlights
                    if (data.total_detected > 0) {
                        if (data.attentiveness_percentage < 50) addHighlight(data.timestamp, data.attentiveness_percentage, 'low');
                        if (data.attentiveness_percentage > 90) addHighlight(data.timestamp, data.attentiveness_percentage, 'high');
                    }

                } catch (error) { console.error("Error updating dashboard:", error); }
            }
            
            // --- 3. HELPER FUNCTION for Highlights ---
            function addHighlight(timestamp, percentage, type) {
                const key = `${timestamp}-${type}`;
                if (addedHighlights[key]) return;
                if(highlightsPlaceholder) highlightsPlaceholder.style.display = 'none';
                const isLow = type === 'low';
                const iconColor = isLow ? 'text-red-500' : 'text-green-500';
                const text = isLow ? 'Attention dropped' : 'Peak engagement';
                const highlightHtml = `<div class="flex items-start gap-3 p-2 border rounded-lg"><svg class="w-5 h-5 mt-0.5 ${iconColor} flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isLow ? 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' : 'M13 7h8m0 0V5m0 8l-8 8-4-4-6 6'}" /></svg><div class="flex-1"><div class="flex justify-between text-sm font-semibold"><p>Time: ${timestamp}s</p><p class="${iconColor}">${percentage}%</p></div><p class="text-xs text-gray-600">${text}</p></div></div>`;
                highlightsContainer.insertAdjacentHTML('afterbegin', highlightHtml);
                addedHighlights[key] = true;
            }

            // --- 4. START EVERYTHING ---
            initializeChart();
            setInterval(updateDashboard, 2000);
        });
    </script>
    @endpush
</x-app-layout>