<!--
    This is the "guest" layout file. It's a minimalist wrapper designed for pages
    that are shown to unauthenticated users, such as the login, registration,
    or password reset pages. Its primary job is to provide a consistent background
    and a centered content area.
-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Head content remains the same --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token: Essential security feature for protecting forms against cross-site request forgery. -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title: Dynamically sets the page title from the application's configuration. -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Assets: Vite handles the compilation and inclusion of CSS and JavaScript. -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" style="background-color: #E1F1EF;">
    <!-- The <body> tag sets the global background color for the entire page. -->

    <!-- Centering Wrapper:
         This div uses Flexbox (`flex`, `items-center`, `justify-center`) to perfectly center
         its child (`<main>`) both vertically and horizontally within the full screen (`min-h-screen`). -->
    <div class="flex items-center justify-center min-h-screen">

        <!-- Main Content Container:
             This is the primary structural block for the page's content. -->
        <main class="w-[100%] max-w-[1800px] h-[90vh] min-h-[600px] bg-transparent">
            <!--
                - It's designed to be an INVISIBLE structural container (`bg-transparent`),
                  allowing the body's background color to show through.
                - Sizing: It spans the full width up to a max of 1800px and takes up 90%
                  of the viewport height, ensuring it looks good on various screen sizes.
            -->

            <!-- Content Slot:
                 This is the Blade directive that injects the specific content from the
                 view using this layout (e.g., the login form from `login.blade.php`). -->
            {{ $slot }}
        </main>
    </div>
</body>

</html>