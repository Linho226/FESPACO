<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - FESPACO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --brand: #006241;
            --brand-dark: #015535;
            --accent: #d97706;
            --bg-1: #0b1220;
            --bg-2: #111827;
            --panel: rgba(255,255,255,.04);
            --panel-border: rgba(255,255,255,.12);
            --text-main: #e5e7eb;
            --text-soft: #9ca3af;
        }

        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, #1f2a44 0%, var(--bg-1) 35%, var(--bg-2) 100%);
            color: var(--text-main);
        }

        .contact-wrap {
            max-width: 1100px;
            margin: 0 auto;
        }

        .hero {
            background: linear-gradient(135deg, rgba(0,98,65,.2), rgba(217,119,6,.16));
            border: 1px solid var(--panel-border);
            border-radius: 22px;
            padding: 2rem;
            backdrop-filter: blur(8px);
            box-shadow: 0 16px 34px rgba(0,0,0,.22);
            margin-bottom: 1.4rem;
        }

        .hero h1 {
            font-size: clamp(2rem, 5vw, 2.8rem);
            line-height: 1.1;
            font-weight: 800;
            margin-bottom: .55rem;
            color: #fff;
        }

        .hero p {
            margin: 0;
            color: var(--text-soft);
            font-size: 1.03rem;
        }

        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1.15fr;
            gap: 1rem;
        }

        .panel {
            background: var(--panel);
            border: 1px solid var(--panel-border);
            border-radius: 16px;
            padding: 1.2rem;
            backdrop-filter: blur(8px);
        }

        .panel h2 {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #fff;
        }

        .contact-info-list {
            display: grid;
            gap: .7rem;
        }

        .info-item {
            background: rgba(255,255,255,.03);
            border: 1px solid var(--panel-border);
            border-radius: 12px;
            padding: .8rem .9rem;
        }

        .info-item .label {
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .25px;
            color: #86efac;
            margin-bottom: .2rem;
            display: block;
        }

        .info-item .value {
            color: #d1d5db;
            margin: 0;
        }

        .contact-form .form-label {
            color: #d1d5db;
            font-weight: 600;
            font-size: .92rem;
        }

        .contact-form .form-control,
        .contact-form .form-select {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.15);
            color: #f3f4f6;
            border-radius: 10px;
            padding: .62rem .75rem;
        }

        .contact-form .form-control::placeholder {
            color: rgba(229,231,235,.5);
        }

        .contact-form .form-control:focus,
        .contact-form .form-select:focus {
            background: rgba(255,255,255,.06);
            border-color: rgba(0,98,65,.65);
            box-shadow: 0 0 0 .18rem rgba(0,98,65,.22);
            color: #f9fafb;
        }

        .btn-send {
            width: 100%;
            background: var(--brand);
            border-color: var(--brand);
            color: #fff;
            font-weight: 700;
            padding: .7rem 1rem;
            border-radius: 10px;
        }

        .btn-send:hover {
            background: var(--brand-dark);
            border-color: var(--brand-dark);
            color: #fff;
        }

        .tiny-note {
            color: var(--text-soft);
            font-size: .83rem;
            margin-top: .7rem;
            margin-bottom: 0;
        }

        @media (max-width: 920px) {
            .contact-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (prefers-color-scheme: light) {
            :root {
                --bg-1: #edf2f7;
                --bg-2: #e6ebf2;
                --panel: rgba(255,255,255,.86);
                --panel-border: rgba(15,23,42,.12);
                --text-main: #111827;
                --text-soft: #4b5563;
            }

            body {
                background: linear-gradient(145deg, #eef2f7 0%, #e7ecf3 100%);
                color: var(--text-main);
            }

            .hero h1 {
                color: #111827;
            }

            .panel h2 {
                color: #111827;
            }

            .info-item .label {
                color: #065f46;
            }

            .info-item .value,
            .contact-form .form-label,
            .tiny-note {
                color: #334155;
            }

            .contact-form .form-control,
            .contact-form .form-select {
                background: #fff;
                border-color: rgba(15,23,42,.18);
                color: #0f172a;
            }

            .contact-form .form-control::placeholder {
                color: rgba(15,23,42,.45);
            }

            .contact-form .form-control:focus,
            .contact-form .form-select:focus {
                background: #fff;
                color: #0f172a;
            }
        }
    </style>
</head>
<body>
    @include('public.navbar')
    <div class="container py-4 py-md-5">
        <div class="contact-wrap">
            <section class="hero">
                <h1>Nous contacter</h1>
                <p>Une question, une proposition de partenariat ou un besoin d'information sur le festival ? Écrivez-nous, notre équipe vous répondra rapidement.</p>
            </section>

            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="contact-grid">
                <aside class="panel">
                    <h2>Coordonnées</h2>
                    <div class="contact-info-list">
                        <div class="info-item">
                            <span class="label">Adresse</span>
                            <p class="value">Ouagadougou, Burkina Faso</p>
                        </div>
                        <div class="info-item">
                            <span class="label">Email</span>
                            <p class="value">contact@fespaco.bf</p>
                        </div>
                        <div class="info-item">
                            <span class="label">Téléphone</span>
                            <p class="value">+226 00 00 00 00</p>
                        </div>
                        <div class="info-item">
                            <span class="label">Disponibilité</span>
                            <p class="value">Lundi au vendredi, 8h00 - 17h00</p>
                        </div>
                    </div>
                    <p class="tiny-note">Vous pouvez nous écrire directement via le formulaire à droite.</p>

                </aside>

                <div class="panel">
                    <h2>Envoyer un message</h2>
                    <form class="contact-form" method="POST" action="{{ route('public.contact.send') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Votre nom complet" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Adresse email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="exemple@mail.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="subject" class="form-label">Sujet</label>
                                <select id="subject" name="subject" class="form-select" required>
                                    <option value="">Sélectionner un sujet</option>
                                    <option value="Information générale" {{ old('subject') === 'Information générale' ? 'selected' : '' }}>Information générale</option>
                                    <option value="Partenariat" {{ old('subject') === 'Partenariat' ? 'selected' : '' }}>Partenariat</option>
                                    <option value="Presse & médias" {{ old('subject') === 'Presse & médias' ? 'selected' : '' }}>Presse & médias</option>
                                    <option value="Support technique" {{ old('subject') === 'Support technique' ? 'selected' : '' }}>Support technique</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Téléphone (optionnel)</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="+226 ...">
                            </div>
                            <div class="col-12">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Décrivez votre demande..." required>{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-send">Envoyer le message</button>
                                <p class="tiny-note">En soumettant ce formulaire, vous acceptez d’être recontacté par l’équipe du FESPACO.</p>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</body>
</html>
