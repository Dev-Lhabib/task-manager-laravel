<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager — Connexion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-brand">
            <div class="auth-brand-icon">✦</div>
            <div class="auth-brand-title">TaskManager</div>
            <div class="auth-brand-sub">Gérez vos tâches efficacement</div>
        </div>

        <div class="auth-card">
            <h1>🔐 Connexion</h1>

            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-options">
                    <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-submit">Se connecter</button>
            </form>

            <div class="auth-footer">
                Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
            </div>
        </div>
    </div>
</body>
</html>
