<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mot de passe oublie - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-deep: #070d1f;
            --bg-mid: #0b1630;
            --panel: rgba(14, 26, 52, 0.78);
            --panel-border: rgba(255, 255, 255, 0.11);
            --text-main: #edf2fb;
            --text-soft: #9db0cf;
            --accent: #f7b32b;
            --accent-strong: #e39b0f;
            --danger: #ff7b7b;
            --ok: #7ce0b2;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Source Sans 3', sans-serif;
            color: var(--text-main);
            background:
                radial-gradient(circle at 10% 15%, rgba(247, 179, 43, 0.1), transparent 28%),
                radial-gradient(circle at 85% 80%, rgba(72, 137, 255, 0.14), transparent 30%),
                linear-gradient(140deg, var(--bg-deep), var(--bg-mid));
        }

        .auth-page {
            min-height: calc(100vh - 72px);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .shell {
            width: 100%;
            max-width: 980px;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            border: 1px solid var(--panel-border);
            border-radius: 22px;
            overflow: hidden;
            background: rgba(3, 8, 20, 0.45);
            backdrop-filter: blur(10px);
            box-shadow: 0 28px 70px rgba(2, 8, 24, 0.55);
        }

        .left {
            padding: 44px 40px;
            background: linear-gradient(165deg, rgba(247, 179, 43, 0.12), rgba(16, 27, 54, 0.2));
            border-right: 1px solid var(--panel-border);
        }

        .brand {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 1.7rem;
            letter-spacing: 0.5px;
            font-weight: 700;
            color: var(--accent);
            text-decoration: none;
        }

        .tag {
            margin-top: 28px;
            display: inline-block;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-soft);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            padding: 7px 12px;
        }

        h1 {
            font-family: 'Space Grotesk', sans-serif;
            margin: 16px 0 10px;
            font-size: clamp(1.8rem, 3vw, 2.2rem);
            line-height: 1.15;
        }

        .lead {
            margin: 0;
            max-width: 42ch;
            color: var(--text-soft);
            font-size: 1.02rem;
            line-height: 1.6;
        }

        .right {
            padding: 38px 34px;
            background: var(--panel);
        }

        .form-title {
            margin: 0 0 18px;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
        }

        .notice {
            border-radius: 10px;
            padding: 10px 12px;
            margin-bottom: 14px;
            font-size: 0.95rem;
        }

        .notice.ok {
            background: rgba(124, 224, 178, 0.12);
            border: 1px solid rgba(124, 224, 178, 0.4);
            color: #caf8e2;
        }

        .notice.err {
            background: rgba(255, 123, 123, 0.12);
            border: 1px solid rgba(255, 123, 123, 0.4);
            color: #ffd0d0;
        }

        .group { margin-bottom: 14px; }

        label {
            display: block;
            margin-bottom: 6px;
            color: var(--text-soft);
            font-size: 0.92rem;
        }

        input[type="email"] {
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(8, 17, 34, 0.65);
            color: var(--text-main);
            border-radius: 11px;
            padding: 11px 13px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-size: 1rem;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(247, 179, 43, 0.18);
        }

        .error {
            margin-top: 5px;
            color: #ffb4b4;
            font-size: 0.86rem;
        }

        .submit {
            width: 100%;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(90deg, var(--accent), var(--accent-strong));
            color: #1f1301;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            padding: 12px;
            margin-top: 8px;
            cursor: pointer;
        }

        .bottom {
            margin-top: 14px;
            text-align: center;
            color: var(--text-soft);
            font-size: 0.92rem;
        }

        .link {
            color: #b8c9e6;
            text-decoration: none;
        }

        .link:hover { color: #ffffff; }

        @media (max-width: 880px) {
            .shell { grid-template-columns: 1fr; }
            .left { border-right: 0; border-bottom: 1px solid var(--panel-border); padding: 28px 24px; }
            .right { padding: 24px; }
        }
    </style>
</head>
<body>
    @include('public.navbar')

    <div class="auth-page">
    <div class="shell">
        <section class="left">
            <a href="{{ route('public.home') }}" class="brand">FESPACO</a>
            <span class="tag">Recuperation compte</span>
            <h1>Mot de passe oublie ?</h1>
            <p class="lead">
                Entrez votre adresse email. Nous vous enverrons un lien securise pour redefinir votre mot de passe.
            </p>
        </section>

        <section class="right">
            <h2 class="form-title">Reinitialiser mon mot de passe</h2>

            @if(session('status'))
                <div class="notice ok">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="notice err">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="group">
                    <label for="email">Adresse email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="submit">Envoyer le lien de reinitialisation</button>
            </form>

            <p class="bottom">
                Vous vous souvenez de votre mot de passe ?
                <a class="link" href="{{ route('login') }}">Retour a la connexion</a>
            </p>
        </section>
    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
