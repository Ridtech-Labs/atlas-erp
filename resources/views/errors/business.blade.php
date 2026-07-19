<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-stone-100 text-stone-900">
        <main class="mx-auto flex min-h-screen max-w-3xl items-center px-6 py-16">
            <div class="w-full rounded-3xl border border-stone-200 bg-white p-10 shadow-sm">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-amber-700">Atlas ERP</p>
                <h1 class="mt-4 text-3xl font-semibold text-stone-900">We could not complete that action</h1>
                <p class="mt-4 text-base leading-7 text-stone-600">{{ $message }}</p>
            </div>
        </main>
    </body>
</html>
