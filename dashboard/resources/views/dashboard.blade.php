<!--
    This Blade view is wrapped by the 'app-layout' component, which provides
    the main application structure (sidebar, header, etc.). This file contains
    the specific content for the user's dashboard.
-->
<x-app-layout>
    <div>
        <!-- =================================================================== -->
        <!-- WELCOME BANNER                                                    -->
        <!-- A prominent banner to greet the logged-in user.                   -->
        <!-- =================================================================== -->
        <div class="relative bg-[#1DE9B6] rounded-2xl shadow-md mb-6 flex items-center">
            
            <!-- Text container for the banner -->
            <div class="p-8 w-full lg:w-3/4">
                <!-- Dynamically displays the name of the currently authenticated user -->
                <h2 class="text-4xl font-bold text-black mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="text-black/80">Your AI dashboard provides real-time insights into student attention and attendance. For any updates to your monitored classes, please get in touch with the Head Teacher.</p>
            </div>

            <!-- Image container for the banner -->
            <!-- The illustrative image is hidden on smaller screens (`lg:block`) to save space -->
            <div class="hidden lg:block w-1/4 h-full">
                <!-- The image is positioned absolutely within its container to create a visually appealing overlap effect -->
                <img src="{{ asset('images/welcome-banner.png') }}" alt="Classroom Illustration" class="drop-shadow-xl absolute bottom-0 right-5 w-auto h-[110%] object-contain">
            </div>

        </div>

        <!-- =================================================================== -->
        <!-- 2x2 WIDGET GRID                                                   -->
        <!-- Main container for the dashboard widgets. It's a single column    -->
        <!-- on small screens and transitions to a two-column grid on large    -->
        <!-- screens (`lg:grid-cols-2`).                                       -->
        <!-- =================================================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- WIDGET 1: Overall Concentration -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                
                <!-- Custom header for the widget, containing the title, navigation, and legend -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Overall Concentration Level</h3>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-400 hover:text-black">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        </button>
                        <span class="font-semibold">Week 13</span>
                        <button class="text-gray-400 hover:text-black">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="h-3 w-3 rounded-full bg-[#1DE9B6]"></span>
                        <span class="text-sm text-gray-500">Avg no.</span>
                    </div>
                </div>
                
                <!-- A container with a fixed height is used to hold the chart canvas. This prevents the chart from resizing awkwardly on page load. -->
                <div class="relative h-80"> 
                    <!-- The `<canvas>` element is the placeholder where Chart.js will draw the chart -->
                    <canvas id="concentrationChart"></canvas>
                </div>
            </div>

            {{-- This Blade directive pushes the enclosed JavaScript into the 'scripts' stack, which is defined in the main app-layout. --}}
            {{-- This keeps the script logically close to its HTML while ensuring it loads at the end of the body. --}}
            @push('scripts')
            <script>
                // The script waits for the entire HTML document to be loaded before trying to find the canvas element, preventing errors.
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('concentrationChart');

                    // --- DUMMY DATA ---
                    // This data is hardcoded for demonstration. In a real application,
                    // this would be fetched from the server via an API call or passed from the controller.
                    const labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                    const barData = [55, 39, 65, 35, 88]; // Daily concentration %
                    const lineData = [20, 45, 60, 40, 95]; // Average trend line

                    new Chart(ctx, {
                        type: 'bar', // Specifies the default chart type.
                        data: {
                            labels: labels,
                            // This chart combines two different dataset types: a line chart and a bar chart.
                            datasets: [
                                {
                                    type: 'line', // This dataset is rendered as a line chart on top.
                                    label: 'Avg no.',
                                    data: lineData,
                                    borderColor: '#0D0D0D',
                                    backgroundColor: '#0D0D0D',
                                    tension: 0.4, // Makes the line smooth and curved instead of jagged.
                                    pointRadius: 5,
                                    pointBackgroundColor: '#0D0D0D',
                                    yAxisID: 'y', // Ties this dataset to the 'y' axis.
                                },
                                {
                                    type: 'bar', // This dataset is the primary bar chart.
                                    label: 'Week 13',
                                    data: barData,
                                    backgroundColor: '#1DE9B6',                      
                                    borderRadius: 8, // Gives the bars rounded top corners.
                                    yAxisID: 'y',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false, // Makes the chart fill its container without being constrained to a specific aspect ratio.
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100, // Sets the upper limit of the y-axis to 100%.
                                    ticks: {
                                        // A callback function to format the y-axis labels to include a '%' symbol.
                                        callback: function(value) {
                                            return value + '%'
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false, // Hides the vertical grid lines for a cleaner look.
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false, // Hides the default Chart.js legend, as we have a custom one in the widget header.
                                },
                                tooltip: {
                                    // Custom tooltip logic can be added here if needed.
                                }
                            }
                        }
                    });
                });
            </script>
            @endpush

            <!-- WIDGET 2: Session Attention Timeline -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Session Attention Timeline</h3>
                    <a href="{{ route('insights') }}" class="text-sm font-medium text-[#1DE9B6] hover:underline">See all</a>
                </div>
                
                <div class="relative h-80">
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>

            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const timelineCtx = document.getElementById('timelineChart');

                    // --- Dummy Data for the Line Chart ---
                    const timelineLabels = ['5', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55', '60'];
                    const attentionData = [22, 25, 18, 28, 45, 60, 75, 70, 65, 80, 82, 85];

                    new Chart(timelineCtx, {
                        type: 'line',
                        data: {
                            labels: timelineLabels,
                            datasets: [{
                                label: 'Attention %',
                                data: attentionData,
                                borderColor: '#1DE9B6',
                                backgroundColor: 'rgba(29, 233, 182, 0.1)', // Light, semi-transparent fill.
                                fill: true, // Creates the filled area under the line.
                                tension: 0.4, // Makes the curve smooth.
                                pointRadius: 0, // Hides the dots on the line for a cleaner look.
                                pointHoverRadius: 6,
                                pointHoverBackgroundColor: '#1DE9B6',
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        stepSize: 10
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Time (minutes)'
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false // Hides the legend as per the design.
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
                                        // Customizes the tooltip to show a more descriptive label.
                                        label: function(context) {
                                            return `Attention: ${context.parsed.y}%`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
            @endpush

            <!-- WIDGET 3: Engagement Score -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Engagement Score</h3>
                    <a href="{{ route('trends') }}" class="text-sm font-medium text-[#1DE9B6] hover:underline">See all</a>
                </div>
                
                <div class="flex items-center justify-center space-x-8">
                    <!-- Wrapper div for the doughnut chart canvas -->
                    <div class="relative h-48 w-48">
                        <canvas id="engagementChart"></canvas>
                    </div>
                    
                    <!-- Text indicator for percentage increase -->
                    <div class="text-left">
                        <p class="text-4xl font-bold flex items-center" style="color: #1DE9B6;">
                            <svg class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                            <span>+8%</span>
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Improved vs. last class
                        </p>
                    </div>
                </div>
            </div>

            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const engagementCtx = document.getElementById('engagementChart');

                    // --- Dummy Data for Donut Chart ---
                    const engagementScore = 80;
                    const remainingScore = 100 - engagementScore;

                    // A custom Chart.js plugin is created to draw the score text directly in the center of the doughnut chart.
                    const centerTextPlugin = {
                        id: 'centerText',
                        afterDraw: (chart) => {
                            let ctx = chart.ctx;
                            ctx.save();
                            let centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                            let centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                            
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            
                            // Draws the main score text (e.g., "80").
                            ctx.font = 'bold 48px Poppins';
                            ctx.fillStyle = '#000000';
                            ctx.fillText(engagementScore, centerX, centerY - 10);

                            // Draws the subtext (e.g., "Per 100").
                            ctx.font = 'normal 16px Poppins';
                            ctx.fillStyle = '#6B7280'; // gray-500
                            ctx.fillText('Per 100', centerX, centerY + 20);

                            ctx.restore();
                        }
                    };

                    new Chart(engagementCtx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [engagementScore, remainingScore],
                                backgroundColor: ['#1DE9B6', '#EF4444'],
                                borderColor: ['#FFFFFF'],
                                borderWidth: 4,
                                cutout: '75%', // Controls the thickness of the doughnut ring.
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { enabled: false }, // Disables tooltips as per the design.
                                centerText: centerTextPlugin // Registers our custom plugin data.
                            }
                        },
                        plugins: [centerTextPlugin] // Activates our custom plugin.
                    });
                });
            </script>
            @endpush

            <!-- WIDGET 4: Weekly Insights & Suggestions -->
            <!-- This is a static widget displaying a list of AI-generated insights. -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Weekly Insights & Suggestions</h3>
                    <a href="{{ route('trends') }}" class="text-sm font-medium text-[#1DE9B6] hover:underline" >See all</a>
                </div>
                
                <div class="space-y-6">
                    
                    <!-- Insight Item 1 (Negative Trend) -->
                    <!-- Each insight is a flex container with a color-coded icon on the left and text on the right. -->
                    <div class="flex items-start space-x-3">
                        <div class="bg-red-100 p-2 rounded-full">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                        </div>
                        <p class="text-gray-700">Tuesday showed the lowest attention (<strong class="font-bold text-black">43%</strong>).</p>
                    </div>
                    
                    <!-- Insight Item 2 (Positive Trend) -->
                    <div class="flex items-start space-x-3">
                        <div class="bg-green-100 p-2 rounded-full">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0V15m0-8l-8 8-4-4-6 6" /></svg>
                        </div>
                        <p class="text-gray-700">Friday engagement increased by <strong class="font-bold text-black">12%</strong> compared to last week.</p>
                    </div>
                    
                    <!-- Insight Item 3 (Suggestion) -->
                    <div class="flex items-start space-x-3">
                        <div class="bg-blue-100 p-2 rounded-full">
                            <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                        </div>
                        <p class="text-gray-700">Consider an activity around the <strong class="font-bold text-black">30 min</strong> mark to maintain focus.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>