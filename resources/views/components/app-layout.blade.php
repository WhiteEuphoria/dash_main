<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'App') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen">
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="py-6">
                {{ $slot }}
            </main>
        </div>
    </body>
    </html>

