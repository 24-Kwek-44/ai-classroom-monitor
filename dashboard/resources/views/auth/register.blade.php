<x-guest-layout>

    <!-- Main flex container holding the two cards -->
    <div class="flex items-center justify-center h-full w-full gap-6 p-6">

        <!-- Card 1: Image Section (No changes needed here) -->
        <div class="w-1/2 h-full hidden lg:flex flex-col justify-end p-12 bg-cover bg-center shadow-lg relative rounded-[30px]"
            style="background-image: url('{{ asset('images/register-wallpaper.jpg') }}');">
            <div class="relative z-10">
                <h1 class="text-5xl font-bold leading-tight text-white"
                    style="text-shadow: 2px 2px 8px rgba(0,0,0,0.7);">
                    JOIN TO MANAGE<br>YOUR STUDENT.
                </h1>
            </div>
        </div>

        <!-- Card 2: Form Section -->
        <div class="w-full lg:w-1/2 h-full flex items-center justify-center p-12 rounded-[30px] shadow-lg relative"
            style="background-color: #0D0D0D;">
            {{-- CHANGE: The main card now has 'position: relative' --}}

            <!-- CHANGE: Logo is now a direct child of the card for correct positioning -->
            <div class="absolute top-6 right-6">
                <img src="{{ asset('images/InsightEdu-Logo.png') }}" alt="InsightEdu Logo" class="h-16">
            </div>

            <div class="w-full max-w-md text-white h-full flex flex-col justify-center">

                <div class="text-center w-full">
                    {{-- The logo has been moved out of this content block --}}
                    <h2 class="text-3xl font-bold mb-4">Welcome to InsightEdu!</h2>
                    <p class="text-[#ACACAC] mb-8">Create your account to start tracking student attendance and managing
                        records effortlessly.</p>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="w-full">
                    @csrf

                    <!-- Auth Toggle Switch (No changes needed here) -->
                    <div class="relative flex items-center bg-[#BAECE5] rounded-full p-1 mb-8">

                        <!-- Active sliding pill with larger gap -->
                        <div class="absolute top-2 bottom-2 left-2 w-[calc(50%-0.5rem)] 
                                        bg-[#01F5D1] rounded-full 
                                        transition-transform duration-300 ease-in-out z-0"
                            style="transform: translateX(100%);">
                        </div>

                        <!-- Text links -->
                        <a href="{{ route('login') }}"
                            class="flex-1 py-2 text-center text-white font-medium z-10 transition">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="flex-1 py-2 text-center text-white font-medium z-10 transition">
                            Register
                        </a>
                    </div>
                    <!-- Name -->
                    <div>
                        <x-input-label for="name" value="Name" class="text-white mb-2" />
                        {{-- The component now handles the styling, so no classes needed here --}}
                        <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus
                            autocomplete="name" placeholder="Enter your Name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Teacher ID -->
                    <div class="mt-4">
                        <x-input-label for="teacher_id" value="Teacher ID" class="text-white mb-2" />
                        <x-text-input id="teacher_id" type="text" name="teacher_id" :value="old('teacher_id')" required
                            autocomplete="username" placeholder="Enter your Teacher ID" />
                        <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" value="Password" class="text-white mb-2" />
                        <div class="relative flex items-center">
                            <x-text-input id="password" type="password" name="password" required
                                autocomplete="new-password" placeholder="••••••••" class="pr-12" />

                            <!-- NOTE: We are now using a CLASS instead of an ID -->
                            <div
                                class="toggle-password-visibility absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer">
                                <!-- The 'eye' icon -->
                                <svg class="eye-icon h-6 w-6 text-[#01F5D1]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- The 'eye-slash' icon -->
                                <svg class="eye-slash-icon h-6 w-6 text-[#01F5D1] hidden" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" value="Confirm Password" class="text-white mb-2" />
                        <!-- This now has the same structure as the field above -->
                        <div class="relative flex items-center">
                            <x-text-input id="password_confirmation" type="password" name="password_confirmation"
                                required autocomplete="new-password" placeholder="••••••••" class="pr-12" />

                            <!-- NOTE: We use the same class name here -->
                            <div
                                class="toggle-password-visibility absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer">
                                <!-- The 'eye' icon -->
                                <svg class="eye-icon h-6 w-6 text-[#01F5D1]" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- The 'eye-slash' icon -->
                                <svg class="eye-slash-icon h-6 w-6 text-[#01F5D1] hidden" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Register button (No changes needed here) -->
                    <div class="flex justify-end mt-8">
                        <button type="submit"
                            class="w-1/2 py-3 bg-[#01F5D1] hover:bg-insight-aqua-dark text-white font-bold rounded-full transition-colors">
                            Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Place this at the bottom of register.blade.php -->
    <script>
        // Find ALL the toggle buttons on the page
        const toggleButtons = document.querySelectorAll('.toggle-password-visibility');

        // Add a click event listener to EACH button
        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Find the password input field that is RIGHT BEFORE this button
                const passwordInput = this.previousElementSibling;

                // Find the icons INSIDE this specific button
                const eyeIcon = this.querySelector('.eye-icon');
                const eyeSlashIcon = this.querySelector('.eye-slash-icon');

                // Toggle the type attribute
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Toggle the icon visibility
                eyeIcon.classList.toggle('hidden');
                eyeSlashIcon.classList.toggle('hidden');
            });
        });
    </script>
</x-guest-layout>