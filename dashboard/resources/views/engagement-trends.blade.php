<!--
    This Blade view displays the "Engagement Trends" page, which is designed for
    analyzing long-term student engagement data. It features summary cards,
    progression and comparison charts, and AI-powered insights. It is wrapped
    by the 'app-layout' component.
-->
<x-app-layout>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Engagement Trends</h1>
                <p class="text-gray-500 mt-1">Track how engagement changes over time to identify improvement patterns</p>
            </div>
            <!-- Filter Dropdowns: Uses Alpine.js for simple, self-contained dropdown interactivity. -->
            <div class="flex items-center gap-4">
                <!-- "All Classes" Dropdown -->
                <!-- Initializes an Alpine component to manage the dropdown's open/closed state. -->
                <div x-data="{ open: false }" class="relative">
                    <!-- The button toggles the 'open' state on click. -->
                    <button @click="open = !open" class="text-sm text-gray-500 font-medium flex items-center gap-2 hover:text-black">
                        <span>All Classes</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <!-- The dropdown content is shown/hidden based on the 'open' state. `@click.away` closes it when clicking outside. -->
                    <div x-show="open" @click.away="open = false" x-transition class="absolute top-full right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10 border">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">All Classes</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Class A</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Class B</a>
                    </div>
                </div>

                <!-- "Semester" Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-sm text-gray-500 font-medium flex items-center gap-2 hover:text-black">
                        <span>Semester</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute top-full right-0 mt-2 w-40 bg-white rounded-md shadow-lg z-10 border">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Semester</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">1 Month</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Full Year</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Summary Cards -->
        <!-- A responsive grid of Key Performance Indicator (KPI) cards that provide a quick overview. -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="bg-white p-4 rounded-2xl shadow-md"><h4 class="text-sm font-medium text-gray-500">Avg Engagement</h4><p class="text-2xl font-bold text-gray-800 mt-1">72%</p><p class="text-xs text-gray-400 mt-1">vs last month</p></div>
            <div class="bg-white p-4 rounded-2xl shadow-md"><h4 class="text-sm font-medium text-gray-500">Highest Week</h4><p class="text-xl font-semibold text-green-500 mt-1">Week 12 (86%)</p><p class="text-xs text-gray-400 mt-1">Peak performance</p></div>
            <div class="bg-white p-4 rounded-2xl shadow-md"><h4 class="text-sm font-medium text-gray-500">Lowest Week</h4><p class="text-xl font-semibold text-red-500 mt-1">Week 3 (61%)</p><p class="text-xs text-gray-400 mt-1">Needs attention</p></div>
            <div class="bg-white p-4 rounded-2xl shadow-md"><h4 class="text-sm font-medium text-gray-500">Target Rate</h4><p class="text-xl font-semibold text-blue-500 mt-1">54%</p><p class="text-xs text-gray-400 mt-1">Above 70% threshold</p></div>
        </div>

        <!-- Main Content Grid: A responsive two-column layout for detailed information. -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Charts (spans 2 of 3 columns on large screens) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Engagement Progression Chart -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold">Engagement Progression</h3>
                    <p class="text-sm text-gray-500 mt-1">Weekly engagement scores with target threshold</p>
                    <div class="relative h-80 mt-4">
                        <canvas id="engagementProgressionChart"></canvas>
                    </div>
                </div>
                <!-- Class Comparison Chart -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold">Class Comparison</h3>
                    <p class="text-sm text-gray-500 mt-1">Current engagement levels across all classes</p>
                    <div class="relative h-64 mt-4">
                        <canvas id="classComparisonChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Right Column: AI-powered Insights and Quick Actions -->
            <div class="space-y-6">
                <!-- Weekly Insights Card -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-2">Weekly Insights & Suggestions</h3>
                    <p class="text-sm text-gray-500 mb-4">AI-powered recommendations based on engagement data</p>
                    <div class="space-y-4">
                        {{-- Each insight is styled with a specific color and icon to convey its meaning (positive, neutral, negative). --}}
                        <div class="p-4 rounded-lg border bg-green-50 border-green-200 flex items-start gap-3"><svg class="w-5 h-5 mt-0.5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M13 7h8m0 0V15m0-8l-8 8-4-4-6 6"/></svg><div><h4 class="font-medium text-green-800 mb-1">Strong Progress</h4><p class="text-sm text-gray-600">Engagement increased by 12% compared to last month</p></div></div>
                        <div class="p-4 rounded-lg border bg-blue-50 border-blue-200 flex items-start gap-3"><svg class="w-5 h-5 mt-0.5 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M9 3.482c.345-.345.89-.345 1.236 0l1.468 1.468a1 1 0 001.414 0l1.468-1.468c.345-.345.89-.345 1.236 0l1.468 1.468a1 1 0 001.414 0l1.468-1.468c.345-.345.89-.345 1.236 0l.354.354a1 1 0 010 1.414l-4.242 4.243a1 1 0 01-1.414 0l-4.243-4.243a1 1 0 010-1.414l.354-.354zM9 15.482c.345-.345.89-.345 1.236 0l1.468 1.468a1 1 0 001.414 0l1.468-1.468c.345-.345.89-.345 1.236 0l1.468 1.468a1 1 0 001.414 0l1.468-1.468c.345-.345.89-.345 1.236 0l.354.354a1 1 0 010 1.414l-4.242 4.243a1 1 0 01-1.414 0l-4.243-4.243a1 1 0 010-1.414l.354-.354z"/></svg><div><h4 class="font-medium text-blue-800 mb-1">Peak Performance</h4><p class="text-sm text-gray-600">Week 12 showed the highest engagement (86%)</p></div></div>
                        <div class="p-4 rounded-lg border bg-orange-50 border-orange-200 flex items-start gap-3"><svg class="w-5 h-5 mt-0.5 text-orange-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg><div><h4 class="font-medium text-orange-800 mb-1">Recent Decline</h4><p class="text-sm text-gray-600">Week 13 engagement dropped to 72% from previous high</p></div></div>
                    </div>
                </div>
                <!-- Quick Actions Card -->
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
                    <div class="space-y-3"><button class="w-full text-left p-3 border rounded-lg hover:bg-gray-50">Schedule Review Meeting</button><button class="w-full text-left p-3 border rounded-lg hover:bg-gray-50">Export Report</button><button class="w-full text-left p-3 border rounded-lg hover:bg-gray-50">Set New Targets</button></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blade directive to push this script block into the 'scripts' stack of the main layout. -->
    @push('scripts')
    <script>
        // Wait for the DOM to be fully loaded before initializing charts.
        document.addEventListener('DOMContentLoaded', function () {
            // --- CHART 1: ENGAGEMENT PROGRESSION (Area Chart) ---
            const progressionCtx = document.getElementById('engagementProgressionChart');
            // Safety check to ensure the script doesn't run if the canvas element isn't on the page.
            if (progressionCtx) {
                // --- DUMMY DATA ---
                // This hardcoded data simulates fetching weekly engagement scores from a server.
                const engagementData = [
                  { week: 'W1', engagement: 58, target: 70 }, { week: 'W2', engagement: 62, target: 70 },
                  { week: 'W3', engagement: 61, target: 70 }, { week: 'W4', engagement: 67, target: 70 },
                  { week: 'W5', engagement: 64, target: 70 }, { week: 'W6', engagement: 71, target: 70 },
                  { week: 'W7', engagement: 75, target: 70 }, { week: 'W8', engagement: 84, target: 70 },
                  { week: 'W9', engagement: 78, target: 70 }, { week: 'W10', engagement: 82, target: 70 },
                  { week: 'W11', engagement: 79, target: 70 }, { week: 'W12', engagement: 86, target: 70 },
                  { week: 'W13', engagement: 72, target: 70 },
                ];
                new Chart(progressionCtx, {
                    type: 'line',
                    data: {
                        labels: engagementData.map(item => item.week), // Creates labels for the X-axis (W1, W2, etc.).
                        datasets: [
                            { 
                                label: 'Engagement', 
                                data: engagementData.map(item => item.engagement), // The actual engagement data.
                                borderColor: '#1DE9B6', 
                                tension: 0.4, // Makes the line smooth and curved.
                                fill: true, // Creates the filled area under the line.
                                // The background fill is a gradient for a more polished look.
                                backgroundColor: (ctx) => { 
                                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 400); 
                                    gradient.addColorStop(0, 'rgba(29, 233, 182, 0.3)');
                                    gradient.addColorStop(1, 'rgba(29, 233, 182, 0)');
                                    return gradient; 
                                } 
                            },
                            { 
                                label: 'Target', 
                                data: engagementData.map(item => item.target), // The target threshold data.
                                borderColor: '#9CA3AF', // Gray color for the target line.
                                borderDash: [5, 5], // Creates a dashed line effect.
                                pointRadius: 0 // Hides points on the target line.
                            }
                        ]
                    }, 
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false, // Allows the chart to fill its container.
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                max: 100, 
                                // Custom callback to format Y-axis labels with a '%' sign.
                                ticks: { callback: (v) => `${v}%` } 
                            } 
                        }, 
                        plugins: { 
                            legend: { display: false } // Hides the default Chart.js legend.
                        } 
                    }
                });
            }

            // --- CHART 2: CLASS COMPARISON (Bar Chart) ---
            const comparisonCtx = document.getElementById('classComparisonChart');
            if (comparisonCtx) {
                // --- DUMMY DATA ---
                // Simulates engagement scores for different classes.
                const classComparisonData = [
                  { class: 'Class A', engagement: 72 }, { class: 'Class B', engagement: 68 },
                  { class: 'Class C', engagement: 79 }, { class: 'Class D', engagement: 65 },
                ];
                new Chart(comparisonCtx, {
                    type: 'bar',
                    data: {
                        labels: classComparisonData.map(item => item.class),
                        datasets: [{ 
                            label: 'Engagement', 
                            data: classComparisonData.map(item => item.engagement), 
                            backgroundColor: '#1DE9B6', 
                            borderRadius: { topLeft: 4, topRight: 4 } // Gives bars rounded top corners.
                        }]
                    }, 
                    // Options are kept concise as they are similar to the previous chart.
                    options: { 
                        responsive: true, maintainAspectRatio: false, 
                        scales: { y: { beginAtZero: true, max: 100, ticks: { callback: (v) => `${v}%` } } }, 
                        plugins: { legend: { display: false } } 
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>