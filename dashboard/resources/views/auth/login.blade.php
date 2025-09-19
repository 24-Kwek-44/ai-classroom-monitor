<!--
    This Blade view uses the 'guest-layout' component as its base wrapper.
    It provides a two-column login interface: one for visual branding (image)
    and one for the interactive form.
-->
<x-guest-layout>
    <!-- 
      Main Centering Container:
      Uses Flexbox to center the two-card layout within the viewport.
      The `gap-6` provides spacing between the two cards on larger screens.
    -->
    <div class="flex items-center justify-center h-full w-full gap-6 p-6">

        <!-- =================================================================== -->
        <!-- Card 1: Image Section (Visual Branding)                           -->
        <!-- =================================================================== -->
        <!-- 
          - This card is purely for aesthetics.
          - `hidden lg:flex`: It's hidden on small/medium screens and appears as a flex container on large screens and up, creating the two-column layout.
          - `bg-cover bg-center`: Ensures the background image scales nicely to cover the entire div.
        -->
        <div class="w-1/2 h-full hidden lg:flex flex-col justify-end p-12 bg-cover bg-center shadow-lg relative rounded-[30px]"
            style="background-image: url('{{ asset('images/login-wallpaper.jpg') }}');">
            {{-- The `asset()` helper generates a full URL to the specified file in the `/public` directory. --}}
            <!-- 
              Text Overlay:
              - A `relative` div with a high `z-index` ensures the text appears on top of any potential overlays.
              - `text-shadow` is used for better readability against the background image.
            -->
            <div class="relative z-10">
                <h1 class="text-5xl font-bold leading-tight text-white"
                    style="text-shadow: 2px 2px 8px rgba(0,0,0,0.7);">
                    MONITOR YOUR<br>STUDENT EASILY.
                </h1>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- Card 2: Login Form Section                                        -->
        <!-- =================================================================== -->
        <!-- 
          - This card contains the actual login form and interactivity.
          - `w-full lg:w-1/2`: Takes up the full width on small screens and half the width on large screens.
        -->
        <div class="w-full lg:w-1/2 h-full flex items-center justify-center p-12 rounded-[30px] shadow-lg relative"
            style="background-color: #0D0D0D;">

            <!-- Logo: Positioned absolutely in the top-right corner of the form card. -->
            <div class="absolute top-6 right-6">
                <img src="{{ asset('images/InsightEdu-Logo.png') }}" alt="InsightEdu Logo" class="h-16">
            </div>

            <!-- This container vertically centers the form content within the card -->
            <div class="w-full max-w-md text-white h-full flex flex-col justify-center">

                <!-- Welcome Header -->
                <div class="text-center w-full">
                    <h2 class="text-3xl font-bold mb-4">Welcome to InsightEdu!</h2>
                    <p class="text-[#ACACAC] mb-8">Access your dashboard to monitor student attendance levels and
                        records in real time.</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf <!-- CSRF Token: A security measure to protect against cross-site request forgery attacks. -->

                    <!-- Auth Toggle Switch (Login/Register) -->
                    <!-- A custom UI component to switch between authentication pages. -->
                    <div class="relative flex items-center bg-[#BAECE5] rounded-full p-1 mb-8">
                        <!-- The sliding 'pill' background. Its position is set via `transform` to be under the 'Login' link, indicating the active page. -->
                        <div class="absolute top-2 bottom-2 left-2 w-[calc(50%-0.5rem)] 
                                        bg-[#01F5D1] rounded-full 
                                        transition-transform duration-300 ease-in-out z-0"
                            style="transform: translateX(0%);">
                        </div>
                        <!-- The clickable links. `z-10` ensures they are on top of the sliding pill. -->
                        <a href="{{ route('login') }}"
                            class="flex-1 py-2 text-center text-white font-medium z-10 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex-1 py-2 text-center text-white font-medium z-10 transition">
                            Register
                        </a>
                    </div>

                    <!-- Teacher ID Input -->
                    <div>
                        <x-input-label for="teacher_id" value="Teacher ID" class="text-white mb-2" />
                        <x-text-input id="teacher_id" type="text" name="teacher_id" :value="old('teacher_id')" required
                            autofocus placeholder="Enter your Teacher ID" />
                        <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" /> <!-- Displays validation errors for the 'teacher_id' field. -->
                    </div>

                    <!-- Password Input -->
                    <div class="mt-4">
                        <x-input-label for="password" value="Password" class="text-white mb-2" />
                        <!-- `relative` container is needed to position the eye icon inside the input field. -->
                        <div class="relative flex items-center">
                            <x-text-input id="password" type="password" name="password" required
                                autocomplete="current-password" placeholder="••••••••" class="pr-12" />
                            <!-- Password visibility toggle button -->
                            <div id="togglePassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer">
                                <!-- Eye icon (visible by default) -->
                                <svg id="eyeIcon" class="h-6 w-6 text-[#01F5D1]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye-slash icon (hidden by default) -->
                                <svg id="eyeSlashIcon" class="h-6 w-6 text-[#01F5D1] hidden" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password Links -->
                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-[#01F5D1] shadow-sm focus:ring-[#01F5D1]"
                                name="remember">
                            <span class="ms-2 text-sm text-gray-400">Remember me</span>
                        </label>

                        @if (Route::has('password.request')) <!-- This link only appears if the 'password.request' route is defined. -->
                            <a class="underline text-sm text-gray-400 hover:text-white"
                                href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="w-1/2 py-3 bg-[#01F5D1] hover:bg-insight-aqua-dark text-white font-bold rounded-full transition-colors">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<!-- JavaScript for Password Visibility Toggle -->
<script>
    // Select the necessary DOM elements for the password toggle functionality.
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');
    const eyeSlashIcon = document.querySelector('#eyeSlashIcon');

    // Add a click event listener to the toggle button.
    togglePassword.addEventListener('click', function (e) {
        // Use a ternary operator to determine the new input type.
        // If the current type is 'password', change it to 'text'; otherwise, change it back to 'password'.
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);

        // Toggle the 'hidden' class on both icons to swap their visibility.
        eyeIcon.classList.toggle('hidden');
        eyeSlashIcon.classList.toggle('hidden');
    });
</script>