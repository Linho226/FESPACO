<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - FESPACO</title>
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
    <link rel="stylesheet" href="/css/public/contact.css">
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
    @include('public.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
