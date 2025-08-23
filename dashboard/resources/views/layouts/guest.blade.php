<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    {{-- Head content remains the same --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" style="background-color: #E1F1EF;">
    <!-- This is the main page background -->
    <div class="flex items-center justify-center min-h-screen">
        <!-- This main element is now just an INVISIBLE structural container.
             It has NO background color itself. -->
        <main class="w-[100%] max-w-[1800px] h-[90vh] min-h-[600px] bg-transparent">
            {{ $slot }}
        </main>
    </div>
</body>

</html>