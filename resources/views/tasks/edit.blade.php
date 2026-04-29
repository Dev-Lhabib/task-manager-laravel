@extends('layouts.app')

@section('title', 'Modifier la tâche')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
@endsection

@section('content')

<div class="form-hero edit-hero animate-fadeup">
    <h1>
        <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Modifier la tâche
    </h1>
    <p>Modifiez les informations de « {{ $task->title }} »</p>
</div>

<div class="glass-card form-card animate-fadeup animate-fadeup-1">
    <form method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title" class="form-label">Titre *</label>
            <input type="text" id="title" name="title" class="form-input"
                value="{{ old('title', $task->title) }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-textarea">{{ old('description', $task->description) }}</textarea>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;">
            <div class="form-group" style="margin-bottom:0;">
                <label for="category_id" class="form-label">Catégorie *</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="">— Choisir —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $task->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label for="status" class="form-label">Statut</label>
                <select id="status" name="status" class="form-select">
                    <option value="todo"        {{ old('status', $task->status) == 'todo'        ? 'selected' : '' }}>À faire</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                    <option value="in_review"   {{ old('status', $task->status) == 'in_review'   ? 'selected' : '' }}>En révision</option>
                    <option value="done"        {{ old('status', $task->status) == 'done'        ? 'selected' : '' }}>Terminé</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom:0;">
                <label for="due_date" class="form-label">Date d'échéance</label>
                <input type="date" id="due_date" name="due_date" class="form-input"
                    value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-accent">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m9 12 2 2 4-4"/></svg>
                Enregistrer
            </button>
            <a href="{{ route('tasks.index') }}" class="btn-cancel">Annuler</a>
        </div>
    </form>
</div>
@endsection
