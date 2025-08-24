<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Session Insights</h1>
                <p class="text-gray-500 mt-1">Deep dive into specific class sessions with minute-by-minute attention analysis</p>
            </div>
            {{-- Dropdown Placeholder --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-sm text-gray-500 font-medium flex items-center gap-2 hover:text-black">
                    <span>Today's Session - 10:00</span>
                    {{-- Chevron Down Icon --}}
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
            <!-- Left Column: Timeline and Video -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold">Attention Timeline</h3>
                    <p class="text-sm text-gray-500 mt-1">Click on highlighted events to jump to video timestamp</p>
                    <div class="relative h-80 mt-4">
                        <canvas id="sessionTimelineChart"></canvas>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Session Recording</h3>
                    <div class="aspect-video bg-gray-900 rounded-lg flex items-center justify-center text-white">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 mx-auto">
                                <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M5.22 14.78a.75.75 0 001.06 0l7.5-7.5a.75.75 0 00-1.06-1.06L5.22 14.78z"/></svg>
                            </div>
                            <p class="font-semibold">Classroom Session Recording</p>
                            <p class="text-xs text-gray-400 mt-1">Current time: 0:00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Stats and Highlights -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-4">Session Statistics</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-center"><span class="text-gray-600">Average Attention</span><span class="font-semibold text-gray-800">68%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Lowest Point</span><span class="font-semibold text-red-500">42%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Highest Point</span><span class="font-semibold text-green-500">95%</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Engagement Score</span><span class="font-semibold text-blue-500">75</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Duration</span><span class="font-semibold text-gray-800">60 min</span></div>
                        <div class="flex justify-between items-center"><span class="text-gray-600">Students Present</span><span class="font-semibold text-gray-800">26/28</span></div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-md">
                    <h3 class="text-xl font-bold mb-2">Session Highlights</h3>
                    <p class="text-sm text-gray-500 mb-4">Key moments during the session</p>
                    <div class="space-y-3">
                        {{-- Highlight Item 1 --}}
                        <div class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <svg class="w-5 h-5 mt-0.5 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div class="flex-1">
                                <div class="flex justify-between text-sm font-semibold"><p>12:40</p><p class="text-red-500">42%</p></div>
                                <p class="text-xs text-gray-600">Attention dropped below 40%</p>
                            </div>
                        </div>
                         {{-- Highlight Item 2 --}}
                        <div class="flex items-start gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                            <svg class="w-5 h-5 mt-0.5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            <div class="flex-1">
                                <div class="flex justify-between text-sm font-semibold"><p>28:10</p><p class="text-green-500">92%</p></div>
                                <p class="text-xs text-gray-600">Peak engagement detected</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sessionCtx = document.getElementById('sessionTimelineChart');
            if (sessionCtx) {
                const sessionData = [
                  { time: 0, attention: 85, highlight: false }, { time: 5, attention: 82, highlight: false },
                  { time: 10, attention: 78, highlight: false }, { time: 12, attention: 42, highlight: true },
                  { time: 15, attention: 65, highlight: false }, { time: 20, attention: 58, highlight: false },
                  { time: 25, attention: 45, highlight: true }, { time: 28, attention: 92, highlight: true },
                  { time: 30, attention: 75, highlight: false }, { time: 35, attention: 68, highlight: false },
                  { time: 40, attention: 88, highlight: false }, { time: 45, attention: 95, highlight: true },
                  { time: 50, attention: 89, highlight: false }, { time: 55, attention: 86, highlight: false },
                  { time: 60, attention: 82, highlight: false },
                ];

              new Chart(sessionCtx, {
                  type: 'line',
                  data: {
                      labels: sessionData.map(item => `${item.time}m`),
                      datasets: [{
                          label: 'Attention', 
                          data: sessionData.map(item => item.attention),
                          // CHANGE: Updated the main line color to your brand's teal
                          borderColor: '#1DE9B6', 
                          tension: 0.4,
                          fill: true, // Let's add the subtle fill like the other charts
                          backgroundColor: (ctx) => { 
                              const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 400); 
                              gradient.addColorStop(0, 'rgba(29, 233, 182, 0.3)'); 
                              gradient.addColorStop(1, 'rgba(29, 233, 182, 0)');   
                              return gradient; 
                          },
                          // The logic for the highlight points remains the same (red/green)
                          pointRadius: sessionData.map(p => p.highlight ? 6 : 0),
                          pointBackgroundColor: sessionData.map(p => p.attention < 50 ? '#EF4444' : '#10B981'),
                          pointBorderColor: '#FFFFFF',
                          pointBorderWidth: 2,
                      }]
                  },
                  options: {
                      responsive: true, maintainAspectRatio: false,
                      scales: { 
                          y: { 
                              beginAtZero: true, 
                              max: 100, 
                              ticks: { 
                                  callback: (v) => `${v}%` 
                              } 
                          } 
                      },
                      plugins: { 
                          legend: { 
                              display: false 
                          } 
                      }
                  }
              });
            }
        });
    </script>
    @endpush
</x-app-layout>