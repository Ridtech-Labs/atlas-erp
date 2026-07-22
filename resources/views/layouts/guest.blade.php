<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&family=geist-mono:400,500,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[var(--atlas-color-text-primary)] antialiased">
        <div class="atlas-auth-shell flex min-h-screen flex-col items-center justify-center px-4 py-8">
            <div class="mb-6">
                <a href="/" wire:navigate>
                    <x-application-logo class="h-16 w-16 fill-current text-[var(--atlas-color-action-primary)]" />
                </a>
            </div>

            <div class="atlas-auth-card w-full max-w-md overflow-hidden px-6 py-6 sm:px-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
