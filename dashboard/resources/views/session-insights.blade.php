<x-app-layout>
    <div class="space-y-6">
        <!-- Header: Restored from the original design with the dropdown -->
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Session Insights</h1>
                <p class="text-gray-500 mt-1">Deep dive into class sessions. Currently viewing live feed.</p>
            </div>
            {{-- Dropdown is kept for switching to past sessions --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-sm text-gray-500 font-medium flex items-center gap-2 hover:text-black">
                    <span>Live Session</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>

                <div x-show="open" 
                    @click.away="open = false" 
                    x-transition
                    class="absolute top-full right-0 mt-2 w-56 bg-white rounded-lg shadow-xl z-10 border">
                    
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Today's Session - 10:00</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Yesterday - 10:00 AM</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Monday - 10:00 AM</a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Layout restored to Timeline on top, Video below -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold">Attention Timeline</h3>
                    <p class="text-sm text-gray-500 mt-1">Real-time analysis of student attention</p>
                    <div class="relative h-80 mt-4">
                        <!-- Canvas ID changed to match the live script -->
                        <canvas id="liveTimelineChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Live Session Feed</h3>
                    <!-- Live video feed integrated into the original design's card -->
                    <div class="aspect-video bg-gray-900 rounded-lg">
                        <img src="http://127.0.0.1:5001/video_feed" class="w-full h-full rounded-lg object-contain" alt="Live AI Video Feed">
                    </div>
                </div>
            </div>

            <!-- Right Column: Stats and Highlights integrated with live IDs -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Session Statistics</h3>
                    <!-- Stats now have IDs for dynamic updates, using labels from original design where applicable -->
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-center"><span class="text-gray-600">Current Attention</span><span id="stat-current" class="font-semibold text-gray-800">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Lowest Point</span><span id="stat-lowest" class="font-semibold text-red-500">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Highest Point</span><span id="stat-highest" class="font-semibold text-green-500">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Session Time</span><span id="stat-duration" class="font-semibold text-gray-800">--s</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Students Detected</span><span id="stat-detected" class="font-semibold text-gray-800">--</span></div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-2">Session Highlights</h3>
                    <p class="text-sm text-gray-500 mb-4">Key moments detected during the session</p>
                    <!-- Highlights container is now dynamic -->
                    <div id="highlights-container" class="space-y-3 max-h-[15rem] overflow-y-auto pr-2">
                        <p id="highlights-placeholder" class="text-xs text-gray-400">Waiting for key events...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Assuming Chart.js is loaded globally or via your app's bundle -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- STATE VARIABLES ---
            let liveChart;
            let lowestPoint = 100;
            let highestPoint = 0;
            let addedHighlights = new Set(); // Using a Set for efficient checks

            // --- GET HTML ELEMENTS ---
            const highlightsContainer = document.getElementById('highlights-container');
            const highlightsPlaceholder = document.getElementById('highlights-placeholder');

            // --- 1. INITIALIZE THE CHART ---
            function initializeChart() {
                const ctx = document.getElementById('liveTimelineChart');
                if (!ctx) return;

                liveChart = new Chart(ctx, {
                    type: 'line',
                    data: { 
                        labels: [], 
                        datasets: [{ 
                            label: 'Attention %', 
                            data: [], 
                            borderColor: '#1DE9B6', 
                            tension: 0.4, 
                            backgroundColor: (context) => {
                                const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                                gradient.addColorStop(0, 'rgba(29, 233, 182, 0.3)');
                                gradient.addColorStop(1, 'rgba(29, 233, 182, 0)');
                                return gradient;
                            },
                            fill: true,
                            pointRadius: 0 // No points by default
                        }] 
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        scales: { 
                            y: { beginAtZero: true, max: 100, ticks: { callback: (v) => `${v}%` } },
                            x: { grid: { display: false } }
                        }, 
                        plugins: { legend: { display: false } } 
                    }
                });
            }

            // --- 2. FUNCTION TO UPDATE DASHBOARD ---
            async function updateDashboard() {
                try {
                    const response = await fetch('/api/get-status');
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();

                    if (!data || data.timestamp === undefined) return;

                    const currentAttention = parseInt(data.attentiveness_percentage, 10);

                    // Update Stats
                    document.getElementById('stat-current').textContent = `${currentAttention}%`;
                    document.getElementById('stat-duration').textContent = `${data.timestamp}s`;
                    document.getElementById('stat-detected').textContent = `${data.total_detected}`;
                    
                    if (data.total_detected > 0) {
                        if (currentAttention < lowestPoint) lowestPoint = currentAttention;
                        if (currentAttention > highestPoint) highestPoint = currentAttention;
                    }
                    document.getElementById('stat-lowest').textContent = `${lowestPoint}%`;
                    document.getElementById('stat-highest').textContent = `${highestPoint}%`;

                    // Update Chart
                    if (liveChart) {
                        const lastLabel = liveChart.data.labels[liveChart.data.labels.length - 1];
                        if (lastLabel !== `${data.timestamp}s`) {
                            liveChart.data.labels.push(`${data.timestamp}s`);
                            liveChart.data.datasets[0].data.push(currentAttention);
                            // Keep the chart from getting too crowded
                            if (liveChart.data.labels.length > 30) {
                                liveChart.data.labels.shift();
                                liveChart.data.datasets[0].data.shift();
                            }
                            liveChart.update('none'); // 'none' for a smoother animation
                        }
                    }
                    
                    // Update Highlights
                    if (data.total_detected > 0) {
                        if (currentAttention < 40) addHighlight(data.timestamp, currentAttention, 'low');
                        if (currentAttention > 90) addHighlight(data.timestamp, currentAttention, 'high');
                    }

                } catch (error) { console.error("Error updating dashboard:", error); }
            }
            
            // --- 3. HELPER FUNCTION for Highlights (Using superior icons from original design) ---
            function addHighlight(timestamp, percentage, type) {
                const key = `${Math.floor(timestamp / 5)}-${type}`; // Group events within 5-sec windows
                if (addedHighlights.has(key)) return;
                
                if(highlightsPlaceholder) highlightsPlaceholder.style.display = 'none';

                const isLow = type === 'low';
                const iconColor = isLow ? 'text-red-500' : 'text-green-500';
                const text = isLow ? 'Attention dropped significantly' : 'Peak engagement detected';
                // Using the better SVG icons from your original design
                const iconSvg = isLow 
                    ? `<svg class="w-5 h-5 mt-0.5 ${iconColor} flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`
                    : `<svg class="w-5 h-5 mt-0.5 ${iconColor} flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>`;
                
                const highlightHtml = `
                    <div class="flex items-start gap-3 p-3 border rounded-lg animate-pulse bg-gray-50/50">
                        ${iconSvg}
                        <div class="flex-1">
                            <div class="flex justify-between text-sm font-semibold"><p>${timestamp}s</p><p class="${iconColor}">${percentage}%</p></div>
                            <p class="text-xs text-gray-600">${text}</p>
                        </div>
                    </div>`;

                highlightsContainer.insertAdjacentHTML('afterbegin', highlightHtml);
                addedHighlights.add(key);
            }

            // --- 4. START EVERYTHING ---
            initializeChart();
            setInterval(updateDashboard, 2000); // Poll for new data every 2 seconds
        });
    </script>
    @endpush
</x-app-layout>