<!-- resources/views/layouts/app.blade.php -->
<html>
<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <title>1VAULT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Style css -->
    <link href="{{ asset('style.min.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
@yield('content')
</body>
</html>
