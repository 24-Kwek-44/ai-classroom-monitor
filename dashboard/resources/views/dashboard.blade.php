<x-app-layout>
    <div>
        <!-- Welcome Banner -->
        <div class="relative bg-[#1DE9B6] rounded-2xl shadow-md mb-6 flex items-center">
            
            <!-- Text container -->
            <div class="p-8 w-full lg:w-3/4">
                <h2 class="text-4xl font-bold text-black mb-2">Welcome back, {{ Auth::user()->name }}!</h2>
                <p class="text-black/80">Your AI dashboard provides real-time insights into student attention and attendance. For any updates to your monitored classes, please get in touch with the Head Teacher.</p>
            </div>

            <!-- Image container -->
            <div class="hidden lg:block w-1/4 h-full">
                <img src="{{ asset('images/welcome-banner.png') }}" alt="Classroom Illustration" class="drop-shadow-xl absolute bottom-0 right-5 w-auto h-[110%] object-contain">
            </div>

        </div>

        <!-- 2x2 Widget Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Widget 1: Overall Concentration -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                
                <!-- === NEW: CUSTOM WIDGET HEADER === -->
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
                
                {{-- Wrapper div to control the chart's size --}}
                <div class="relative h-80"> 
                    <canvas id="concentrationChart"></canvas>
                </div>
            </div>

            {{-- This script needs to be placed at the end of the dashboard.blade.php file --}}
            @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('concentrationChart');

                    // --- Dummy Data (we will replace this with real data later) ---
                    const labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                    const barData = [55, 39, 65, 35, 88]; // Daily concentration %
                    const lineData = [20, 45, 60, 40, 95]; // Average trend line

                    new Chart(ctx, {
                        type: 'bar', // This is a bar chart
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    type: 'line', // This dataset is a line chart on top
                                    label: 'Avg no.',
                                    data: lineData,
                                    borderColor: '#0D0D0D', // Dark line color
                                    backgroundColor: '#0D0D0D',
                                    tension: 0.4, // Makes the line smooth
                                    pointRadius: 5,
                                    pointBackgroundColor: '#0D0D0D',
                                    yAxisID: 'y',
                                },
                                {
                                    type: 'bar', // This dataset is the bar chart
                                    label: 'Week 13',
                                    data: barData,
                                    backgroundColor: '#1DE9B6', // Teal bar color                       
                                    borderRadius: 8,
                                    yAxisID: 'y',
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        // Format ticks to show '%'
                                        callback: function(value) {
                                            return value + '%'
                                        }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false, // Hide vertical grid lines
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'end',
                                    display: false, // This will hide the default legend
                                },
                                tooltip: {
                                    // Custom tooltip logic can be added here
                                }
                            }
                        }
                    });
                });
            </script>
            @endpush

            <!-- Widget 2: Session Attention Timeline -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Session Attention Timeline</h3>
                    <a href="{{ route('insights') }}" class="text-sm font-medium text-[#1DE9B6] hover:underline">See all</a>
                </div>
                
                {{-- Wrapper div to control size and prevent resizing animation --}}
                <div class="relative h-80">
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>

            {{-- The script for this chart will be pushed to the 'scripts' stack --}}
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
                                borderColor: '#1DE9B6', // Teal line color
                                backgroundColor: 'rgba(29, 233, 182, 0.1)', // Light teal fill color
                                fill: true,
                                tension: 0.4, // Makes the curve smooth
                                pointRadius: 0, // Hides the points on the line
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
                                    display: false // Hides the legend as per your design
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                    callbacks: {
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

            <!-- Widget 3: Engagement Score -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Engagement Score</h3>
                    <a href="{{ route('trends') }}" class="text-sm font-medium text-[#1DE9B6] hover:underline">See all</a>
                </div>
                
                <div class="flex items-center justify-center space-x-8">
                    {{-- Wrapper div for the chart canvas --}}
                    <div class="relative h-48 w-48">
                        <canvas id="engagementChart"></canvas>
                    </div>
                    
                    {{-- Percentage increase indicator --}}
                    <div class="text-left">
                        {{-- Main Percentage --}}
                        <p class="text-4xl font-bold flex items-center" style="color: #1DE9B6;">
                            <svg class="h-8 w-8 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                            <span>+8%</span>
                        </p>
                        {{-- Subtext --}}
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

                    // Custom plugin to draw text in the middle
                    const centerTextPlugin = {
                        id: 'centerText',
                        afterDraw: (chart) => {
                            let ctx = chart.ctx;
                            ctx.save();
                            let centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                            let centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                            
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            
                            // Main score text
                            ctx.font = 'bold 48px Poppins';
                            ctx.fillStyle = '#000000';
                            ctx.fillText(engagementScore, centerX, centerY - 10);

                            // "Per 100" subtext
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
                                backgroundColor: [
                                    '#1DE9B6', // Teal for the score
                                    '#EF4444'  // Red for the remaining part
                                ],
                                borderColor: [
                                    '#FFFFFF' // White border for separation
                                ],
                                borderWidth: 4,
                                cutout: '75%', // Makes the donut thinner
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    enabled: false, // Disable tooltips as per design
                                },
                                centerText: centerTextPlugin // Register our custom plugin
                            }
                        },
                        plugins: [centerTextPlugin] // Activate our custom plugin
                    });
                });
            </script>
            @endpush

            <!-- Widget 4: Weekly Insights & Suggestions -->
            <div class="bg-white p-6 rounded-2xl shadow-md">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Weekly Insights & Suggestions</h3>
                    <a href="{{ route('trends') }}" class="text-sm font-medium text-[#1DE9B6] hover:underline" >See all</a>
                </div>
                
                {{-- List of insights --}}
                <div class="space-y-6">
                    
                    {{-- Insight Item 1 --}}
                    <div class="flex items-start space-x-3">
                        <div class="bg-red-100 p-2 rounded-full">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" /></svg>
                        </div>
                        <p class="text-gray-700">Tuesday showed the lowest attention (<strong class="font-bold text-black">43%</strong>).</p>
                    </div>
                    
                    {{-- Insight Item 2 --}}
                    <div class="flex items-start space-x-3">
                        <div class="bg-green-100 p-2 rounded-full">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0V15m0-8l-8 8-4-4-6 6" /></svg>
                        </div>
                        <p class="text-gray-700">Friday engagement increased by <strong class="font-bold text-black">12%</strong> compared to last week.</p>
                    </div>
                    
                    {{-- Insight Item 3 --}}
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