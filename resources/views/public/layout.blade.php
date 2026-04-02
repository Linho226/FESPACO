<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FESPACO')</title>
    <script>
        (() => {
            const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
            const applyTheme = () => {
                document.documentElement.setAttribute('data-bs-theme', mediaQuery.matches ? 'dark' : 'light');
            };

            applyTheme();

            if (typeof mediaQuery.addEventListener === 'function') {
                mediaQuery.addEventListener('change', applyTheme);
            } else {
                mediaQuery.addListener(applyTheme);
            }
        })();
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            color-scheme: light dark;
        }

        [data-bs-theme="light"] {
            --public-body-bg: #f8fafc;
            --public-body-text: #1f2937;
        }

        [data-bs-theme="dark"] {
            --public-body-bg: #0f1722;
            --public-body-text: #e5e7eb;
        }

        body {
            background: var(--public-body-bg);
            color: var(--public-body-text);
        }
    </style>
</head>
<body>
    @include('public.navbar')
    <div class="container py-4">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>