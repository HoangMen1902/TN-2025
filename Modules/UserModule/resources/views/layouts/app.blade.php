<!-- Modules/UserModule/Resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>    @yield('title')</title>
    @livewireStyles
    <link rel="stylesheet" href="{{ asset('css/auth/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
    @yield('content')

    @livewireScripts
</body>
</html>
