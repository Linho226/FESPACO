<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'FESPACO')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; color: #222; }
    </style>
</head>
<body>
    @include('public.navbar')
    <div class="container py-4">
        @yield('content')
    </div>
</body>
</html>