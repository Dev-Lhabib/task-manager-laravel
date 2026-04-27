<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager — @yield('title', 'Mes tâches')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; color: #1a1a1a; }
        nav { background: #185FA5; padding: 1rem 2rem; display: flex; align-items: center; justify-content: space-between; }
        nav .brand { color: #fff; font-size: 1.2rem; font-weight: 700; text-decoration: none; }
        nav .nav-links { display: flex; gap: 1rem; align-items: center; }
        nav a { color: #fff; text-decoration: none; font-size: 0.9rem; }
        nav a:hover { text-decoration: underline; }
        nav form button { background: none; border: 1px solid #fff; color: #fff; padding: 4px 12px; border-radius: 4px; cursor: pointer; font-size: 0.9rem; }
        .container { max-width: 960px; margin: 2rem auto; padding: 0 1rem; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px 16px; border-radius: 6px; margin-bottom: 1rem; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px 16px; border-radius: 6px; margin-bottom: 1rem; }
    </style>
    @yield('styles')
</head>
<body>

<nav>
    <a href="{{ route('tasks.index') }}" class="brand">✅ Task Manager</a>
    <div class="nav-links">
        @auth
            <span style="color:#fff;font-size:.9rem">{{ auth()->user()->name }}</span>
            <a href="{{ route('tasks.index') }}">Mes tâches</a>
            <a href="{{ route('tasks.create') }}">+ Nouvelle tâche</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Déconnexion</button>
            </form>
        @endauth

        @guest
            <a href="{{ route('login') }}">Connexion</a>
            <a href="{{ route('register') }}">Inscription</a>
        @endguest
    </div>
</nav>

<div class="container">
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <ul style="list-style:none">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>

@yield('scripts')
</body>
</html>
