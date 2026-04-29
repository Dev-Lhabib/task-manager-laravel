<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskManager — Mot de passe oublié</title>
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
            <h1>🔑 Mot de passe oublié</h1>
            <p class="auth-desc">
                Pas de souci ! Indiquez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>

            @if(session('status'))
                <div class="alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="votre@email.com">
                </div>

                <button type="submit" class="btn-submit">Envoyer le lien de réinitialisation</button>
            </form>

            <div class="auth-footer">
                <a href="{{ route('login') }}">← Retour à la connexion</a>
            </div>
        </div>
    </div>
</body>
</html>
