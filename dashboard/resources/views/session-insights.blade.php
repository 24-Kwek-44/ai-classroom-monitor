<!--
    This Blade view displays the "Session Insights" page, which is designed to show a
    live feed of student engagement data, including a video stream, a real-time chart,
    and dynamically updated statistics and highlights. It is wrapped by the 'app-layout' component.
-->
<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Session Insights</h1>
                <p class="text-gray-500 mt-1">Deep dive into class sessions. Currently viewing live feed.</p>
            </div>
            <!-- Dropdown for session selection, powered by Alpine.js -->
            {{-- This allows users to switch between the live feed and historical session data (future functionality). --}}
            <div x-data="{ open: false }" class="relative">
                <!-- The button toggles the 'open' state of the dropdown. -->
                <button @click="open = !open" class="text-sm text-gray-500 font-medium flex items-center gap-2 hover:text-black">
                    <span>Live Session</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>

                <!-- The dropdown menu itself. `x-show` controls its visibility, and `@click.away` closes it. -->
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

        <!-- Main Content Grid: Responsive layout that stacks on mobile and becomes a 2/3 + 1/3 grid on larger screens. -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column (spans 2 of 3 columns on large screens) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Attention Timeline Chart -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold">Attention Timeline</h3>
                    <p class="text-sm text-gray-500 mt-1">Real-time analysis of student attention</p>
                    <div class="relative h-80 mt-4">
                        <!-- The canvas element that Chart.js will use to draw the live graph. -->
                        <canvas id="liveTimelineChart"></canvas>
                    </div>
                </div>
                <!-- Live Video Feed -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Live Session Feed</h3>
                    <!-- The `<img>` tag's `src` points to a video stream, likely from a separate server (e.g., a Flask app) running the AI model. -->
                    <div class="aspect-video bg-gray-900 rounded-lg">
                        <img src="http://127.0.0.1:5001/video_feed" class="w-full h-full rounded-lg object-contain" alt="Live AI Video Feed">
                    </div>
                </div>
            </div>

            <!-- Right Column: Contains live stats and key event highlights. -->
            <div class="space-y-6">
                <!-- Session Statistics Card -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Session Statistics</h3>
                    <!-- Each stat has a unique ID so the JavaScript can easily target and update its value. -->
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-center"><span class="text-gray-600">Current Attention</span><span id="stat-current" class="font-semibold text-gray-800">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Lowest Point</span><span id="stat-lowest" class="font-semibold text-red-500">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Highest Point</span><span id="stat-highest" class="font-semibold text-green-500">--%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Session Time</span><span id="stat-duration" class="font-semibold text-gray-800">--s</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Students Detected</span><span id="stat-detected" class="font-semibold text-gray-800">--</span></div>
                    </div>
                </div>
                <!-- Session Highlights Card -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-2">Session Highlights</h3>
                    <p class="text-sm text-gray-500 mb-4">Key moments detected during the session</p>
                    <!-- This container will be dynamically populated with highlight events by the JavaScript. -->
                    <div id="highlights-container" class="space-y-3 max-h-[15rem] overflow-y-auto pr-2">
                        <!-- This placeholder is visible initially and will be hidden once the first highlight appears. -->
                        <p id="highlights-placeholder" class="text-xs text-gray-400">Waiting for key events...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pushes this entire script block to the 'scripts' stack in the main app layout. -->
    @push('scripts')
    <script>
        // Wait for the DOM to be fully loaded before executing any JavaScript.
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- STATE VARIABLES ---
            // These variables hold the state of the dashboard throughout the session.
            let liveChart; // Will hold the Chart.js instance.
            let lowestPoint = 100; // Initialized high to easily find the first low point.
            let highestPoint = 0; // Initialized low to easily find the first high point.
            let addedHighlights = new Set(); // A Set is used to efficiently track and prevent duplicate highlight events.

            // --- GET HTML ELEMENT REFERENCES ---
            // Caching these elements improves performance by avoiding repeated DOM queries.
            const highlightsContainer = document.getElementById('highlights-container');
            const highlightsPlaceholder = document.getElementById('highlights-placeholder');

            // --- 1. INITIALIZE THE CHART ---
            // This function creates the initial, empty Chart.js chart.
            function initializeChart() {
                const ctx = document.getElementById('liveTimelineChart');
                if (!ctx) return; // Safety check in case the canvas isn't found.

                liveChart = new Chart(ctx, {
                    type: 'line',
                    data: { 
                        labels: [], // Starts with no data points.
                        datasets: [{ 
                            label: 'Attention %', 
                            data: [], // Starts with no data points.
                            borderColor: '#1DE9B6', 
                            tension: 0.4, // Creates smooth, curved lines.
                            // The background fill is a gradient for a more polished look.
                            backgroundColor: (context) => {
                                const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                                gradient.addColorStop(0, 'rgba(29, 233, 182, 0.3)');
                                gradient.addColorStop(1, 'rgba(29, 233, 182, 0)');
                                return gradient;
                            },
                            fill: true,
                            pointRadius: 0 // Hides points on the line for a cleaner appearance.
                        }] 
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, 
                        scales: { 
                            y: { beginAtZero: true, max: 100, ticks: { callback: (v) => `${v}%` } }, // Y-axis from 0-100%
                            x: { grid: { display: false } } // Hides vertical grid lines.
                        }, 
                        plugins: { legend: { display: false } } // Hides the default chart legend.
                    }
                });
            }

            // --- 2. FUNCTION TO UPDATE DASHBOARD (THE CORE LOGIC) ---
            // This async function fetches data from the API and updates all relevant UI elements.
            async function updateDashboard() {
                try {
                    // Fetch the latest status data from the API endpoint.
                    const response = await fetch('/api/get-status');
                    if (!response.ok) throw new Error('Network response was not ok');
                    const data = await response.json();

                    // If data is invalid or empty, stop execution for this cycle.
                    if (!data || data.timestamp === undefined) return;

                    const currentAttention = parseInt(data.attentiveness_percentage, 10);

                    // A) Update the Statistics Card
                    document.getElementById('stat-current').textContent = `${currentAttention}%`;
                    document.getElementById('stat-duration').textContent = `${data.timestamp}s`;
                    document.getElementById('stat-detected').textContent = `${data.total_detected}`;
                    
                    if (data.total_detected > 0) {
                        // Update lowest/highest points only if students are detected.
                        if (currentAttention < lowestPoint) lowestPoint = currentAttention;
                        if (currentAttention > highestPoint) highestPoint = currentAttention;
                    }
                    document.getElementById('stat-lowest').textContent = `${lowestPoint}%`;
                    document.getElementById('stat-highest').textContent = `${highestPoint}%`;

                    // B) Update the Live Chart
                    if (liveChart) {
                        const lastLabel = liveChart.data.labels[liveChart.data.labels.length - 1];
                        // Add a new data point only if the timestamp has changed to avoid duplicates.
                        if (lastLabel !== `${data.timestamp}s`) {
                            liveChart.data.labels.push(`${data.timestamp}s`);
                            liveChart.data.datasets[0].data.push(currentAttention);
                            // To keep the chart readable, remove old data points after a certain limit (e.g., 30 points).
                            if (liveChart.data.labels.length > 30) {
                                liveChart.data.labels.shift(); // Removes the first (oldest) label.
                                liveChart.data.datasets[0].data.shift(); // Removes the first (oldest) data point.
                            }
                            liveChart.update('none'); // Update the chart with a smooth, non-jarring animation.
                        }
                    }
                    
                    // C) Update the Highlights Card
                    if (data.total_detected > 0) {
                        // Define conditions for creating a highlight event.
                        if (currentAttention < 40) addHighlight(data.timestamp, currentAttention, 'low');
                        if (currentAttention > 90) addHighlight(data.timestamp, currentAttention, 'high');
                    }

                } catch (error) { console.error("Error updating dashboard:", error); }
            }
            
            // --- 3. HELPER FUNCTION for Adding Highlights ---
            // This function handles the creation and insertion of highlight elements.
            function addHighlight(timestamp, percentage, type) {
                // To prevent spamming highlights, group events within 5-second windows and only add one per type.
                const key = `${Math.floor(timestamp / 5)}-${type}`; 
                if (addedHighlights.has(key)) return; // If this event has already been logged, do nothing.
                
                // Hide the initial placeholder text once the first highlight is added.
                if(highlightsPlaceholder) highlightsPlaceholder.style.display = 'none';

                // Define styles and content based on whether the event is 'low' or 'high' attention.
                const isLow = type === 'low';
                const iconColor = isLow ? 'text-red-500' : 'text-green-500';
                const text = isLow ? 'Attention dropped significantly' : 'Peak engagement detected';
                const iconSvg = isLow 
                    ? `<svg class="w-5 h-5 mt-0.5 ${iconColor} flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`
                    : `<svg class="w-5 h-5 mt-0.5 ${iconColor} flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>`;
                
                // Create the HTML for the new highlight element using a template literal.
                const highlightHtml = `
                    <div class="flex items-start gap-3 p-3 border rounded-lg animate-pulse bg-gray-50/50">
                        ${iconSvg}
                        <div class="flex-1">
                            <div class="flex justify-between text-sm font-semibold"><p>${timestamp}s</p><p class="${iconColor}">${percentage}%</p></div>
                            <p class="text-xs text-gray-600">${text}</p>
                        </div>
                    </div>`;

                // Add the new highlight to the top of the container and log its key.
                highlightsContainer.insertAdjacentHTML('afterbegin', highlightHtml);
                addedHighlights.add(key);
            }

            // --- 4. START THE LIVE UPDATE PROCESS ---
            initializeChart(); // First, create the empty chart.
            setInterval(updateDashboard, 2000); // Then, start polling the API for new data every 2 seconds.
        });
    </script>
    @endpush
</x-app-layout>