<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'BeeBook'}}</title>
    @vite('resources/css/app.css')</head>
    <link rel="stylesheet" href="{{ asset('/assets/styles/main.css')}}">
    {{ $styles ?? '' }}
<body>
    {{ $slot }}




    <!-- script -->
    {{ $scripts ?? ''}}
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js" integrity="sha256-kmHvs0B+OpCW5GVHUNjv9rOmY0IvSIRcf7zGUDTDQM8=" crossorigin="anonymous"></script>
</body>
</html>