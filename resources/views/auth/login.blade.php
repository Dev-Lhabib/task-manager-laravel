@extends('layouts.app')

@section('title', 'Connexion')

@section('styles')
<style>
    .auth-card { max-width: 420px; margin: 3rem auto; background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
    .auth-card h1 { font-size: 1.4rem; margin-bottom: 1.5rem; color: #185FA5; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .9rem; margin-bottom: 4px; color: #444; }
    .form-group input { width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; font-size: .95rem; }
    .form-group input:focus { outline: none; border-color: #185FA5; }
    .btn-primary { width: 100%; background: #185FA5; color: #fff; border: none; padding: 10px; border-radius: 6px; font-size: 1rem; cursor: pointer; margin-top: .5rem; }
    .btn-primary:hover { background: #0C447C; }
    .auth-footer { text-align: center; margin-top: 1rem; font-size: .9rem; color: #666; }
    .auth-footer a { color: #185FA5; }
</style>
@endsection

@section('content')
<div class="auth-card">
    <h1>🔐 Connexion</h1>

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

        <button type="submit" class="btn-primary">Se connecter</button>
    </form>

    <div class="auth-footer">
        Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a>
    </div>
</div>
@endsection
