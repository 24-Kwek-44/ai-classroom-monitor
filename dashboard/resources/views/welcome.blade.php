<!--
    This is the main public-facing landing page for the application.
    It serves as the marketing front door, explaining what the product does
    and encouraging users to sign up or log in.
-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> <!-- Sets the document language based on the app's locale -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- SEO & Metadata: Title and description for search engines and social sharing -->
    <title>{{ config('app.name', 'INSIGHTEDU') }} - AI-Powered Student Engagement Monitoring</title>
    <meta name="description"
        content="Transform your classroom with AI-driven student concentration and engagement monitoring. Real-time insights for better teaching outcomes.">

    <!-- Fonts: Imports the 'Instrument Sans' font from Google Fonts via Bunny Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts and Stylesheets: Uses Vite to bundle and include compiled CSS and JavaScript assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-100 text-gray-800">
    <!-- =================================================================== -->
    <!-- HEADER & NAVIGATION                                                 -->
    <!-- =================================================================== -->
    <header class="bg-black shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo and Branding -->
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/InsightEdu-Logo.png') }}" alt="InsightEdu Logo" class="h-10 w-15"> <!-- The 'asset()' helper generates a correct URL to the public folder -->
                    <div>
                        <h1 class="text-xl font-bold text-white">INSIGHTEDU</h1>
                        <p class="text-sm text-gray-500">AI-Powered Education Analytics</p>
                    </div>
                </div>

                <!-- Authentication Links Block -->
                <!-- Checks if the 'login' route exists before rendering navigation -->
                @if (Route::has('login'))
                    <nav class="flex items-center gap-4">
                        <!-- @auth directive: Content is shown only if the user is currently logged in -->
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="inline-flex items-center px-6 py-3 bg-[#1DE9B6] hover:bg-teal-500 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                Go to Dashboard
                            </a>
                        <!-- @else directive: Content is shown if the user is a guest (not logged in) -->
                        @else
                            <a href="{{ route('login') }}"
                                class="text-gray-600 hover:text-teal-600 font-medium transition-colors duration-200">
                                Sign In
                            </a>

                            <!-- Checks if the 'register' route exists before showing the 'Get Started' button -->
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center px-6 py-3 bg-[#1DE9B6] hover:bg-teal-500 text-white font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <main>
        <!-- =================================================================== -->
        <!-- HERO SECTION                                                      -->
        <!-- The primary section that grabs the user's attention.              -->
        <!-- =================================================================== -->
        <section class="relative py-20 px-6 overflow-hidden bg-white">
            <div class="absolute inset-0 bg-gradient-to-br from-teal-400 to-green-500 opacity-10"></div> <!-- Decorative background gradient -->
            <div class="relative max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Column: Marketing copy and main call-to-action buttons -->
                    <div>
                        <h1 class="text-5xl font-bold text-gray-900 leading-tight mb-6">
                            Transform Education with
                            <span class="text-teal-600">AI-Powered</span>
                            Student Insights
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                            Monitor student concentration and engagement in real-time using advanced AI analysis.
                            Get actionable insights to enhance learning outcomes and create more effective teaching
                            strategies.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center px-8 py-4 bg-[#1DE9B6] hover:bg-teal-500 text-white font-semibold rounded-xl transition-all duration-200 hover:shadow-lg hover:scale-105">
                                    Start Free Trial
                                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            @endif
                            <a href="#features"
                                class="inline-flex items-center justify-center px-8 py-4 border-2 border-gray-300 hover:border-[#1DE9B6] text-gray-700 hover:text-teal-600 font-semibold rounded-xl transition-colors duration-200">
                                Learn More
                            </a>
                        </div>
                    </div>
                    <!-- Right Column: A visual preview of the application's dashboard -->
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
                            <!-- Faux window controls -->
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <div class="ml-auto text-sm text-gray-500 font-medium">Live Dashboard</div>
                            </div>
                            <!-- Mini Dashboard Preview UI elements -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-teal-50 rounded-lg">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">Class Average Attention</h4>
                                        <p class="text-sm text-gray-600">Current session</p>
                                    </div>
                                    <div class="text-2xl font-bold text-teal-600">78%</div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="p-3 bg-gray-200 rounded-lg text-center">
                                        <div class="text-lg font-semibold text-gray-800">24</div>
                                        <div class="text-sm text-gray-600">Students</div>
                                    </div>
                                    <div class="p-3 bg-gray-200 rounded-lg text-center">
                                        <div class="text-lg font-semibold text-gray-800">42min</div>
                                        <div class="text-sm text-gray-600">Session</div>
                                    </div>
                                </div>
                                <!-- Faux bar chart -->
                                <div class="h-20 bg-gray-100 rounded-lg flex items-end justify-between p-3">
                                    <div class="w-4 bg-[#1DE9B6] rounded-t" style="height: 60%"></div>
                                    <div class="w-4 bg-[#1DE9B6] rounded-t" style="height: 80%"></div>
                                    <div class="w-4 bg-[#1DE9B6] rounded-t" style="height: 45%"></div>
                                    <div class="w-4 bg-[#1DE9B6] rounded-t" style="height: 70%"></div>
                                    <div class="w-4 bg-[#1DE9B6] rounded-t" style="height: 85%"></div>
                                    <div class="w-4 bg-[#1DE9B6] rounded-t" style="height: 55%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================================================================== -->
        <!-- FEATURES SECTION                                                    -->
        <!-- Highlights the key features of the application.                   -->
        <!-- =================================================================== -->
        <section id="features" class="py-20 px-6 bg-white">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">
                        Powerful Features for Modern Education
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Leverage cutting-edge AI technology to gain deep insights into student behavior
                        and optimize your teaching approach for maximum impact.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature Item 1: Real-time Attention Tracking -->
                    <div
                        class="group p-8 bg-gray-100 hover:bg-white rounded-2xl hover:shadow-lg transition-all duration-300 border border-gray-200">
                        <div
                            class="w-16 h-16 bg-[#1DE9B6] rounded-xl flex items-center justify-center mb-6 group-hover:bg-teal-500 transition-colors">
                            <!-- Icon -->
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Real-time Attention Tracking</h3>
                        <p class="text-gray-600">
                            Monitor student attention levels in real-time using advanced computer vision
                            and facial expression analysis.
                        </p>
                    </div>

                    <!-- Feature Item 2: Engagement Analytics -->
                    <div
                        class="group p-8 bg-gray-100 hover:bg-white rounded-2xl hover:shadow-lg transition-all duration-300 border border-gray-200">
                        <div
                            class="w-16 h-16 bg-[#1DE9B6] rounded-xl flex items-center justify-center mb-6 group-hover:bg-teal-500 transition-colors">
                            <!-- Icon -->
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Engagement Analytics</h3>
                        <p class="text-gray-600">
                            Get detailed analytics on student engagement patterns and identify
                            optimal teaching moments for maximum impact.
                        </p>
                    </div>

                    <!-- Feature Item 3: AI-Powered Insights -->
                    <div
                        class="group p-8 bg-gray-100 hover:bg-white rounded-2xl hover:shadow-lg transition-all duration-300 border border-gray-200">
                        <div
                            class="w-16 h-16 bg-[#1DE9B6] rounded-xl flex items-center justify-center mb-6 group-hover:bg-teal-500 transition-colors">
                            <!-- Icon -->
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">AI-Powered Insights</h3>
                        <p class="text-gray-600">
                            Receive intelligent recommendations and actionable insights to improve
                            your teaching effectiveness and student outcomes.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================================================================== -->
        <!-- CALL TO ACTION (CTA) SECTION                                        -->
        <!-- A final, strong encouragement for the user to sign up.            -->
        <!-- =================================================================== -->
        <section class="py-20 px-6 bg-[#1DE9B6]">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold text-white mb-6">
                    Ready to Transform Your Classroom?
                </h2>
                <p class="text-xl text-white/80 mb-8 leading-relaxed">
                    Join thousands of educators who are already using INSIGHTEDU to create more
                    engaging and effective learning experiences.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center justify-center px-8 py-4 bg-white hover:bg-gray-100 text-teal-600 font-semibold rounded-xl transition-all duration-200 hover:shadow-lg">
                            Start Your Free Trial
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </a>
                    @endif
                    <a href="#"
                        class="inline-flex items-center justify-center px-8 py-4 border-2 border-white hover:bg-white hover:text-teal-600 text-white font-semibold rounded-xl transition-all duration-200">
                        Schedule Demo
                    </a>
                </div>
                <p class="text-white/70 text-sm mt-6">
                    No credit card required • 14-day free trial • Cancel anytime
                </p>
            </div>
        </section>
    </main>

    <!-- =================================================================== -->
    <!-- FOOTER                                                              -->
    <!-- Contains branding, navigation links, and legal information.       -->
    <!-- =================================================================== -->
    <footer class="bg-black text-white py-12 px-6">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Footer Column 1: Branding and description -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('images/InsightEdu-Logo.png') }}" alt="InsightEdu Logo" class="h-8 w-10">
                        <h3 class="text-xl font-bold">INSIGHTEDU</h3>
                    </div>
                    <p class="text-gray-400 mb-4 max-w-md">
                        Empowering educators with AI-driven insights to create more engaging
                        and effective learning experiences for students worldwide.
                    </p>
                </div>
                <!-- Footer Column 2: Product links -->
                <div>
                    <h4 class="font-semibold mb-4">Product</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-[#1DE9B6] transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-[#1DE9B6] transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-[#1DE9B6] transition-colors">Demo</a></li>
                        <li><a href="#" class="hover:text-[#1DE9B6] transition-colors">API</a></li>
                    </ul>
                </div>
                <!-- Footer Column 3: Support links -->
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-[#1DE9B6] transition-colors">Help Center</a></li>
                        <li><a href="#" class="hover:text-[#1DE9B6] transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-[#1DE9B6] transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-[#1DE9B6] transition-colors">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <!-- Footer Bottom: Copyright notice -->
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} INSIGHTEDU. All rights reserved.</p> <!-- PHP `date('Y')` dynamically inserts the current year -->
            </div>
        </div>
    </footer>
</body>

</html>