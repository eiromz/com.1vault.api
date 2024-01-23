<!-- resources/views/layouts/app.blade.php -->
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}" type="text/css" media="all" />
</head>
<body>
@yield('content')
</body>
</html>
