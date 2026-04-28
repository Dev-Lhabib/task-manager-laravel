@extends('layouts.app')

@section('title', 'Mon profil')

@php
    $user = Auth::user();
    // Single query for both counts
    $stats = $user->tasks()
        ->selectRaw("count(*) as total")
        ->selectRaw("sum(status = 'done') as done")
        ->first();
    $totalTasks = (int) $stats->total;
    $doneCount = (int) $stats->done;
    $completionRate = $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100) : 0;
@endphp

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')

{{-- Profile Hero --}}
<div class="profile-hero animate-fadeup">
    <div class="profile-hero-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
    <div class="profile-hero-info">
        <div class="profile-hero-name">{{ $user->name }}</div>
        <div class="profile-hero-email">{{ $user->email }}</div>
        <div class="profile-hero-stats">
            <div class="ph-stat">
                <div class="ph-val">{{ $totalTasks }}</div>
                <div class="ph-lbl">Tâches</div>
            </div>
            <div class="ph-stat">
                <div class="ph-val">{{ $completionRate }}%</div>
                <div class="ph-lbl">Complétion</div>
            </div>
            <div class="ph-stat">
                <div class="ph-val">{{ $user->created_at->format('d/m/Y') }}</div>
                <div class="ph-lbl">Membre depuis</div>
            </div>
        </div>
    </div>
</div>

<div class="profile-grid">
    {{-- Update Profile Info --}}
    <div class="glass-card profile-section animate-fadeup animate-fadeup-1">
        <div class="profile-section-header">
            <div class="profile-section-icon" style="background:#eef2ff;color:#6366f1;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5.5 21a7.5 7.5 0 0 1 13 0"/></svg>
            </div>
            <div>
                <div class="profile-section-title">Informations du profil</div>
                <div class="profile-section-desc">Mettez à jour vos informations personnelles</div>
            </div>
        </div>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" id="name" name="name" class="form-input"
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                @if($errors->get('name'))
                    @foreach($errors->get('name') as $msg)
                        <div class="form-error">{{ $msg }}</div>
                    @endforeach
                @endif
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" id="email" name="email" class="form-input"
                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                @if($errors->get('email'))
                    @foreach($errors->get('email') as $msg)
                        <div class="form-error">{{ $msg }}</div>
                    @endforeach
                @endif

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p style="font-size:.82rem;color:var(--text-secondary);margin-top:6px;">
                        Votre email n'est pas vérifié.
                        <button form="send-verification" style="color:var(--accent);text-decoration:underline;background:none;border:none;cursor:pointer;font-size:.82rem;">
                            Renvoyer le lien de vérification
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <div class="form-success" style="margin-top:4px;">
                            ✓ Un nouveau lien a été envoyé.
                        </div>
                    @endif
                @endif
            </div>

            <div style="display:flex;align-items:center;gap:12px;">
                <button type="submit" class="btn btn-accent">Enregistrer</button>
                @if (session('status') === 'profile-updated')
                    <span class="form-success">✓ Profil mis à jour !</span>
                @endif
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="glass-card profile-section animate-fadeup animate-fadeup-2">
        <div class="profile-section-header">
            <div class="profile-section-icon" style="background:#fef3c7;color:#d97706;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/><circle cx="12" cy="16" r="1"/></svg>
            </div>
            <div>
                <div class="profile-section-title">Mot de passe</div>
                <div class="profile-section-desc">Assurez-vous d'utiliser un mot de passe robuste</div>
            </div>
        </div>

        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="update_password_current_password" class="form-label">Mot de passe actuel</label>
                <input type="password" id="update_password_current_password" name="current_password"
                    class="form-input" autocomplete="current-password" placeholder="••••••••">
                @if($errors->updatePassword->get('current_password'))
                    @foreach($errors->updatePassword->get('current_password') as $msg)
                        <div class="form-error">{{ $msg }}</div>
                    @endforeach
                @endif
            </div>

            <div class="form-group">
                <label for="update_password_password" class="form-label">Nouveau mot de passe</label>
                <input type="password" id="update_password_password" name="password"
                    class="form-input" autocomplete="new-password" placeholder="••••••••">
                @if($errors->updatePassword->get('password'))
                    @foreach($errors->updatePassword->get('password') as $msg)
                        <div class="form-error">{{ $msg }}</div>
                    @endforeach
                @endif
            </div>

            <div class="form-group">
                <label for="update_password_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                <input type="password" id="update_password_password_confirmation" name="password_confirmation"
                    class="form-input" autocomplete="new-password" placeholder="••••••••">
                @if($errors->updatePassword->get('password_confirmation'))
                    @foreach($errors->updatePassword->get('password_confirmation') as $msg)
                        <div class="form-error">{{ $msg }}</div>
                    @endforeach
                @endif
            </div>

            <div style="display:flex;align-items:center;gap:12px;">
                <button type="submit" class="btn btn-accent">Mettre à jour</button>
                @if (session('status') === 'password-updated')
                    <span class="form-success">✓ Mot de passe mis à jour !</span>
                @endif
            </div>
        </form>
    </div>

    {{-- Delete Account --}}
    <div class="glass-card profile-section profile-full delete-zone animate-fadeup animate-fadeup-3">
        <div class="profile-section-header">
            <div class="profile-section-icon" style="background:#fee2e2;color:#ef4444;">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4m0 4h.01"/></svg>
            </div>
            <div>
                <div class="profile-section-title" style="color:#dc2626;">Supprimer le compte</div>
                <div class="profile-section-desc">Action irréversible</div>
            </div>
        </div>
        <p class="delete-warning">
            Une fois votre compte supprimé, toutes vos données et tâches seront définitivement effacées.
            Assurez-vous de sauvegarder toute information importante avant de continuer.
        </p>
        <button class="btn btn-danger" onclick="document.getElementById('deleteModal').classList.add('show')">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
            Supprimer mon compte
        </button>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="delete-confirm-modal" id="deleteModal" data-show="{{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }}">
    <div class="modal-card">
        <h3>⚠️ Êtes-vous sûr(e) ?</h3>
        <p>Cette action est irréversible. Toutes vos tâches et données seront supprimées définitivement. Entrez votre mot de passe pour confirmer.</p>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')
            <div class="form-group">
                <label for="delete_password" class="form-label">Mot de passe</label>
                <input type="password" id="delete_password" name="password" class="form-input"
                    placeholder="Entrez votre mot de passe" required>
                @if($errors->userDeletion->get('password'))
                    @foreach($errors->userDeletion->get('password') as $msg)
                        <div class="form-error">{{ $msg }}</div>
                    @endforeach
                @endif
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('deleteModal').classList.remove('show')">
                    Annuler
                </button>
                <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/profile.js') }}"></script>
@endsection
