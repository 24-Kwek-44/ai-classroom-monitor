<!--
    This is a responsive navigation bar component.
    It uses Alpine.js for interactive elements like the user dropdown and the mobile hamburger menu.
    The `x-data="{ open: false }"` directive initializes an Alpine component with a state variable `open`
    set to `false`, which controls the visibility of the mobile menu.
-->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu (Desktop View) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <!-- This uses a reusable Blade component for the application logo -->
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <!-- These links are hidden on small screens (`hidden`) and displayed as flex items on medium screens and up (`sm:flex`) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <!-- This Blade component handles the styling for a navigation link, including its active state -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }} <!-- The `__()` helper is used for localization (translation) -->
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown (for the authenticated user) -->
            <!-- This dropdown is also hidden on small screens and visible on medium screens and up -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- A reusable dropdown Blade component -->
                <x-dropdown align="right" width="48">
                    <!-- The "trigger" slot defines the button that will toggle the dropdown open/closed -->
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <!-- Dynamically displays the name of the currently authenticated user -->
                            <div>{{ Auth::user()->name }}</div>

                            <!-- Dropdown arrow icon -->
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <!-- The "content" slot defines the actual menu items within the dropdown -->
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication: Logout functionality -->
                        <!-- Logout is a POST request for security, so it's wrapped in a form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf <!-- CSRF protection token -->

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"> <!-- This JavaScript submits the parent form when the link is clicked -->
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger Menu Button (Mobile View) -->
            <!-- This button is only visible on small screens (`sm:hidden`) -->
            <div class="-me-2 flex items-center sm:hidden">
                <!-- Alpine.js `@click` directive toggles the `open` state variable from true to false and vice-versa -->
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <!-- Alpine.js `:class` binding toggles the visibility of the two icons based on the `open` state -->
                        <!-- Hamburger icon (three lines), shown when the menu is closed (`!open`) -->
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <!-- Close icon ('X'), shown when the menu is open (`open`) -->
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile View Content) -->
    <!-- This entire div is shown or hidden based on the `open` state, and is always hidden on medium screens and up (`sm:hidden`) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- This is a separate Blade component for a responsive navigation link -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <!-- Displays the user's name and email in the mobile menu -->
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email ?? 'No Email' }}</div> <!-- `??` is the null coalescing operator, providing a fallback -->
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication (Logout for mobile) -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>