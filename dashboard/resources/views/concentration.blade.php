<!--
    This Blade view displays the "Concentration Level" page, which is designed for
    analyzing long-term student concentration data. It features a main trend chart
    and a series of summary cards providing key statistics at a glance.
    It is wrapped by the 'app-layout' component.
-->
<x-app-layout>
  <div class="space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Concentration Level</h1>
      <p class="text-gray-500 mt-1">Track student concentration across multiple periods to identify long-term patterns
      </p>
    </div>

    <!-- Main Content Grid -->
    <!-- This grid is responsive: single column on mobile, transitioning to a 3/4 + 1/4 layout on extra-large screens. -->
    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
      <!-- Main Chart Card (spans 3 of 4 columns on large screens) -->
      <div class="xl:col-span-3 bg-white p-6 rounded-2xl shadow-md">
        <!-- Card Header with Title and Filters -->
        <div class="flex flex-wrap justify-between items-center mb-4">
          <h3 class="text-xl font-bold">Concentration Trends</h3>
          <div class="flex items-center gap-4 mt-4 sm:mt-0">
            <!-- Dropdown Filters: Uses Alpine.js for simple, self-contained dropdown interactivity. -->
            
            <!-- Class Selection Dropdown -->
            <!-- Initializes an Alpine component to manage the dropdown's open/closed state. -->
            <div x-data="{ open: false }" class="relative">
              <!-- The button toggles the 'open' state on click. -->
              <button @click="open = !open"
                class="px-4 py-2 bg-gray-100 rounded-md text-sm w-32 text-left flex justify-between items-center">
                <span>Class A</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <!-- The dropdown content is shown/hidden based on the 'open' state. `@click.away` closes it when clicking outside. -->
              <div x-show="open" @click.away="open = false"
                class="absolute top-full right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-10">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Class A</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Class B</a>
              </div>
            </div>

            <!-- Time Period Dropdown (Weekly/Monthly) -->
            <div x-data="{ open: false }" class="relative">
              <button @click="open = !open"
                class="px-4 py-2 bg-gray-100 rounded-md text-sm w-32 text-left flex justify-between items-center">
                <span>Weekly</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div x-show="open" @click.away="open = false"
                class="absolute top-full right-0 mt-2 w-32 bg-white rounded-md shadow-lg z-10">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Weekly</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Monthly</a>
              </div>
            </div>
          </div>
        </div>
        <!-- Chart Container: A container with a fixed height to hold the chart canvas and prevent layout shifts on load. -->
        <div class="relative h-96">
          <canvas id="concentrationTrendsChart"></canvas>
        </div>
      </div>

      <!-- Sidebar: Summary Cards Column -->
      <!-- A vertical stack of small cards providing key performance indicators (KPIs) at a glance. -->
      <div class="space-y-4">
        <!-- Summary Card: Current Average -->
        <div class="bg-white p-4 rounded-2xl shadow-md">
          <h4 class="text-sm font-medium text-gray-500 mb-2">Current Average</h4>
          <div class="flex items-center gap-2">
            <span class="text-3xl font-bold text-gray-800">72%</span>
            <div class="flex items-center text-green-500 text-sm">
              <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 7h8m0 0V15m0-8l-8 8-4-4-6 6" />
              </svg>
              <span>+5%</span>
            </div>
          </div>
          <p class="text-xs text-gray-400 mt-1">vs last period</p>
        </div>

        <!-- Summary Card: Highest Period -->
        <div class="bg-white p-4 rounded-2xl shadow-md">
          <h4 class="text-sm font-medium text-gray-500 flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 7h8m0 0V15m0-8l-8 8-4-4-6 6" />
            </svg>
            Highest Period
          </h4>
          <p class="font-semibold text-gray-800">Week 12 (88%)</p>
          <p class="text-xs text-gray-400 mt-1">Peak performance</p>
        </div>

        <!-- Summary Card: Lowest Period -->
        <div class="bg-white p-4 rounded-2xl shadow-md">
          <h4 class="text-sm font-medium text-gray-500 flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
            </svg>
            Lowest Period
          </h4>
          <p class="font-semibold text-gray-800">Week 3 (61%)</p>
          <p class="text-xs text-gray-400 mt-1">Needs attention</p>
        </div>

        <!-- Summary Card: Time Period -->
        <div class="bg-white p-4 rounded-2xl shadow-md">
          <h4 class="text-sm font-medium text-gray-500 flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Time Period
          </h4>
          <p class="font-semibold text-gray-800">13 Weeks</p>
          <p class="text-xs text-gray-400 mt-1">Data range</p>
        </div>

        <!-- Summary Card: Class Size -->
        <div class="bg-white p-4 rounded-2xl shadow-md">
          <h4 class="text-sm font-medium text-gray-500 flex items-center gap-2 mb-2">
            <svg class="w-4 h-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.084-1.29-.24-1.9M17 20c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zM7 17a3 3 0 01-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3zm0 0H2v-2a3 3 0 015.356-1.857M7 17v-2c0-.653.084-1.29.24-1.9M7 17c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3z" />
            </svg>
            Class Size
          </h4>
          <p class="font-semibold text-gray-800">28 Students</p>
          <p class="text-xs text-gray-400 mt-1">Active participants</p>
        </div>
      </div>
    </div>
  </div>

<!-- Blade directive to push this script block into the 'scripts' stack of the main layout. -->
@push('scripts')
<script>
    // Wait for the DOM to be fully loaded before initializing the chart.
    document.addEventListener('DOMContentLoaded', function () {
        const trendsCtx = document.getElementById('concentrationTrendsChart');
        // A safety check to ensure the script doesn't run if the canvas element isn't on the page.
        if (trendsCtx) {
            
            // --- DUMMY DATA ---
            // This hardcoded data simulates fetching weekly concentration trends from a server.
            // In a real application, this would be replaced with an API call or data from the controller.
            const weeklyData = [
              { period: 'Week 1', concentration: 68, trend: 65 }, { period: 'Week 2', concentration: 72, trend: 68 },
              { period: 'Week 3', concentration: 61, trend: 70 }, { period: 'Week 4', concentration: 75, trend: 72 },
              { period: 'Week 5', concentration: 69, trend: 74 }, { period: 'Week 6', concentration: 78, trend: 75 },
              { period: 'Week 7', concentration: 82, trend: 77 }, { period: 'Week 8', concentration: 76, trend: 79 },
              { period: 'Week 9', concentration: 71, trend: 78 }, { period: 'Week 10', concentration: 85, trend: 79 },
              { period: 'Week 11', concentration: 79, trend: 81 }, { period: 'Week 12', concentration: 88, trend: 82 },
              { period: 'Week 13', concentration: 84, trend: 84 },
            ];

            // Initialize a new Chart.js instance on the canvas element.
            new Chart(trendsCtx, {
                type: 'bar', // The default type is 'bar', but we will override one dataset to be a 'line'.
                data: {
                    labels: weeklyData.map(item => item.period), // Maps the data to create labels for the X-axis.
                    // This chart combines two dataset types for a comprehensive view.
                    datasets: [
                        {
                            type: 'line', label: 'Trend', data: weeklyData.map(item => item.trend),
                            borderColor: '#0D0D0D', borderWidth: 3, pointBackgroundColor: '#0D0D0D',
                            pointRadius: 5, pointBorderColor: '#FFFFFF', pointBorderWidth: 2,
                            tension: 0.4, // This makes the line smooth and curved.
                            yAxisID: 'y'
                        },
                        {
                            type: 'bar', label: 'Concentration', data: weeklyData.map(item => item.concentration),
                            backgroundColor: '#1DE9B6',
                            borderRadius: { topLeft: 4, topRight: 4 }, // Gives the bars rounded top corners.
                            yAxisID: 'y'
                        }
                    ]
                },
                // Configuration options to customize the chart's appearance and behavior.
                options: {
                    responsive: true, maintainAspectRatio: false, // Makes the chart fill its container's dimensions.
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            max: 100, // Sets the upper limit of the y-axis to 100%.
                            ticks: { 
                                // Custom callback to format the Y-axis labels to include a '%' sign.
                                callback: (value) => `${value}%` 
                            } 
                        },
                        x: { 
                            grid: { display: false } // Hides the vertical grid lines for a cleaner look.
                        }
                    },
                    plugins: { 
                        legend: { display: false }, // Hides the default Chart.js legend.
                        tooltip: { 
                            mode: 'index', // Shows tooltips for all datasets at the same x-axis index.
                            intersect: false 
                        } 
                    }
                }
            });
          }
      });
  </script>
  @endpush
</x-app-layout>