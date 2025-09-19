<!--
    This Blade view uses the 'guest-layout' component as its base wrapper.
    It provides a two-column registration interface: one for visual branding (image)
    and one for the interactive form.
-->
<x-guest-layout>
    <!-- 
      Main Centering Container:
      Uses Flexbox to center the two-card layout within the viewport.
      The `gap-6` provides spacing between the two cards on larger screens.
    -->
    <div class="flex items-center justify-center min-h-screen w-full gap-6 p-6">

        <!-- =================================================================== -->
        <!-- Card 1: Image Section (Visual Branding)                           -->
        <!-- =================================================================== -->
        <!-- 
          - This card is purely for aesthetics.
          - `hidden lg:flex`: It's hidden on small/medium screens and appears as a flex container on large screens and up, creating the two-column layout.
          - `bg-cover bg-center`: Ensures the background image scales nicely to cover the entire div.
        -->
        <div class="w-1/2 h-[90vh] hidden lg:flex flex-col justify-end p-12 bg-cover bg-center shadow-lg relative rounded-[30px]"
            style="background-image: url('{{ asset('images/register-wallpaper.jpg') }}');">
            {{-- The `asset()` helper generates a full URL to the specified file in the `/public` directory. --}}
            <!-- 
              Text Overlay:
              - A `relative` div with a high `z-index` ensures the text appears on top of any potential overlays.
              - `text-shadow` is used for better readability against the background image.
            -->
            <div class="relative z-10">
                <h1 class="text-5xl font-bold leading-tight text-white"
                    style="text-shadow: 2px 2px 8px rgba(0,0,0,0.7);">
                    JOIN TO MANAGE<br>YOUR STUDENT.
                </h1>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- Card 2: Registration Form Section                                 -->
        <!-- =================================================================== -->
        <!-- 
          - This card contains the actual registration form and interactivity.
          - `w-full lg:w-1/2`: Takes up the full width on small screens and half the width on large screens.
          - `overflow-y-auto`: Allows the content to scroll vertically if it exceeds the card's height on smaller screens.
        -->
        <div class="w-full lg:w-1/2 h-[90vh] flex items-center justify-center p-12 rounded-[30px] shadow-lg relative overflow-y-auto"
             style="background-color: #0D0D0D;">
            
            <!-- Logo: Positioned absolutely in the top-right corner of the form card. -->
            <div class="absolute top-6 right-6">
                <img src="{{ asset('images/InsightEdu-Logo.png') }}" alt="InsightEdu Logo" class="h-16">
            </div>

            <div class="w-full max-w-md text-white">
                <!-- Welcome Header -->
                <div class="text-center w-full mb-8">
                    <h2 class="text-3xl font-bold mb-4">Welcome to InsightEdu!</h2>
                    <p class="text-[#ACACAC]">
                        Create your account to start tracking student attendance and managing records effortlessly.
                    </p>
                </div>

                <!-- Auth Toggle Switch (Login/Register) -->
                <!-- A custom UI component to switch between authentication pages. -->
                <div class="relative flex items-center bg-[#BAECE5] rounded-full p-1 mb-8">
                    <!-- The sliding 'pill' background. Its position is hardcoded via `transform` to be under the 'Register' link, indicating the active page. -->
                    <div class="absolute top-1 bottom-1 w-[calc(50%-0.25rem)] bg-[#01F5D1] rounded-full transition-transform duration-300 ease-in-out z-0"
                         style="transform: translateX(calc(100% + 0.25rem)); left: 0.25rem;">
                    </div>
                    
                    <!-- The clickable links. `z-10` ensures they are on top of the sliding pill. -->
                    <a href="{{ route('login') }}" class="flex-1 py-2 text-center text-white font-medium z-10 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="flex-1 py-2 text-center text-white font-medium z-10 transition">
                        Register
                    </a>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="w-full space-y-4">
                    @csrf <!-- CSRF Token: A security measure to protect against cross-site request forgery attacks. -->

                    <!-- Name Input -->
                    <div>
                        <x-input-label for="name" value="Name" class="text-white mb-2" />
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your Name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" /> <!-- Displays validation errors for the 'name' field. -->
                    </div>

                    <!-- Teacher ID Input -->
                    <div>
                        <x-input-label for="teacher_id" value="Teacher ID" class="text-white mb-2" />
                        <x-text-input id="teacher_id" type="text" name="teacher_id" :value="old('teacher_id')" required autocomplete="username" placeholder="Enter your Teacher ID" />
                        <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                    </div>

                    <!-- Password Input -->
                    <div>
                        <x-input-label for="password" value="Password" class="text-white mb-2" />
                        <div class="relative flex items-center">
                            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" class="pr-12" />
                            <!-- Password visibility toggle button -->
                            <button type="button" class="toggle-password absolute right-4 flex items-center cursor-pointer">
                                <svg class="eye-icon h-6 w-6 text-[#01F5D1]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-off-icon h-6 w-6 text-[#01F5D1] hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password Input -->
                    <div>
                        <x-input-label for="password_confirmation" value="Confirm Password" class="text-white mb-2" />
                        <div class="relative flex items-center">
                            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" class="pr-12" />
                             <button type="button" class="toggle-password absolute right-4 flex items-center cursor-pointer">
                                <svg class="eye-icon h-6 w-6 text-[#01F5D1]" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="eye-off-icon h-6 w-6 text-[#01F5D1] hidden" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="w-1/2 py-3 bg-[#01F5D1] hover:bg-opacity-80 text-white font-bold rounded-full transition-colors">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Password Visibility Toggle -->
    <script>
        // Wait for the entire HTML document to be loaded and parsed before running the script.
        document.addEventListener('DOMContentLoaded', function () {
            // Select all buttons that have the 'toggle-password' class.
            const toggleButtons = document.querySelectorAll('.toggle-password');

            // Loop through each of the selected buttons.
            toggleButtons.forEach(button => {
                // Add a 'click' event listener to each button.
                button.addEventListener('click', function () {
                    // Find the password input field, which is the direct sibling before the button.
                    const input = this.previousElementSibling;
                    // Find the 'eye' and 'eye-off' icons within the clicked button.
                    const eyeIcon = this.querySelector('.eye-icon');
                    const eyeOffIcon = this.querySelector('.eye-off-icon');

                    // Check the current type of the input field.
                    if (input.type === 'password') {
                        // If it's a password, change it to text to make it visible.
                        input.type = 'text';
                        // Hide the 'eye' icon and show the 'eye-off' icon.
                        eyeIcon.classList.add('hidden');
                        eyeOffIcon.classList.remove('hidden');
                    } else {
                        // If it's text, change it back to password to hide it.
                        input.type = 'password';
                        // Show the 'eye' icon and hide the 'eye-off' icon.
                        eyeIcon.classList.remove('hidden');
                        eyeOffIcon.classList.add('hidden');
                    }
                });
            });
        });
    </script>
</x-guest-layout>