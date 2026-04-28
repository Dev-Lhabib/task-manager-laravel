@extends('layouts.app')

@section('title', 'Modifier la tâche')

@section('styles')
<style>
    .form-card { max-width:600px; margin:0 auto; background:#fff; padding:2rem; border-radius:10px; box-shadow:0 1px 4px rgba(0,0,0,.06); }
    .form-card h1 { font-size:1.3rem; margin-bottom:1.5rem; color:#1a1a2e; }
    .form-group { margin-bottom:1.1rem; }
    .form-group label { display:block; font-size:.9rem; margin-bottom:4px; color:#444; font-weight:500; }
    .form-group input, .form-group textarea, .form-group select { width:100%; padding:8px 12px; border:1px solid #ccc; border-radius:6px; font-size:.95rem; font-family:inherit; }
    .form-group input:focus, .form-group textarea:focus, .form-group select:focus { outline:none; border-color:#185FA5; }
    .form-group textarea { height:120px; resize:vertical; }
    .form-actions { display:flex; gap:10px; margin-top:1.5rem; }
    .btn-primary { background:#185FA5; color:#fff; border:none; padding:10px 20px; border-radius:6px; font-size:.95rem; cursor:pointer; }
    .btn-secondary { background:#f0f0f0; color:#333; border:1px solid #ddd; padding:10px 20px; border-radius:6px; font-size:.95rem; text-decoration:none; }
</style>
@endsection

@section('content')
<div class="form-card">
    <h1>✏️ Modifier la tâche</h1>

    <form method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Titre *</label>
            <input type="text" id="title" name="title"
                value="{{ old('title', $task->title) }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description', $task->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="category_id">Catégorie *</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Choisir une catégorie --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('category_id', $task->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="status">Statut</label>
            <select id="status" name="status">
                <option value="todo"        {{ old('status', $task->status) == 'todo'        ? 'selected' : '' }}>À faire</option>
                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                <option value="done"        {{ old('status', $task->status) == 'done'        ? 'selected' : '' }}>Terminé</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Enregistrer</button>
            <a href="{{ route('tasks.index') }}" class="btn-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
