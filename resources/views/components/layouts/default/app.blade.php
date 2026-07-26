<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <x-layouts.default.navbar />
    </header>

    <main>
        {{ $slot }}
    </main>

    <footer>
        <x-layouts.default.footer />
    </footer>
</body>
</html>