@extends('layouts.app')

@section('title', 'Nouvelle tâche')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
@endsection

@section('content')

<div class="form-hero create-hero animate-fadeup">
    <h1>
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8m-4-4h8"/></svg>
        Nouvelle tâche
    </h1>
    <p>Remplissez les informations ci-dessous pour créer une nouvelle tâche.</p>
</div>

<div class="glass-card form-card animate-fadeup animate-fadeup-1">
    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <div class="form-group">
            <label for="title" class="form-label">Titre *</label>
            <input type="text" id="title" name="title" class="form-input"
                value="{{ old('title') }}" required autofocus placeholder="Ex: Finaliser le rapport...">
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-textarea"
                placeholder="Décrivez votre tâche en détail...">{{ old('description') }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label for="category_id" class="form-label">Catégorie *</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="">— Choisir —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label for="status" class="form-label">Statut</label>
                <select id="status" name="status" class="form-select">
                    <option value="todo"        {{ old('status') == 'todo'        ? 'selected' : '' }}>À faire</option>
                    <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                    <option value="in_review"   {{ old('status') == 'in_review'   ? 'selected' : '' }}>En révision</option>
                    <option value="done"        {{ old('status') == 'done'        ? 'selected' : '' }}>Terminé</option>
                </select>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-accent">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m9 12 2 2 4-4"/></svg>
                Créer la tâche
            </button>
            <a href="{{ route('tasks.index') }}" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>
@endsection
