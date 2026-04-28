@extends('layouts.app')

@section('title', 'Mes tâches')

@section('styles')
<style>
    .page-header { display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem; }
    .page-header h1 { font-size:1.5rem; color:#1a1a2e; }
    .btn-primary { background:#185FA5; color:#fff; padding:8px 16px; border-radius:6px; text-decoration:none; font-size:.9rem; border:none; cursor:pointer; }
    .btn-primary:hover { background:#0C447C; }
    .filters { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:1.5rem; background:#fff; padding:14px 16px; border-radius:10px; box-shadow:0 1px 4px rgba(0,0,0,.06); }
    .filters select { padding:6px 10px; border:1px solid #ccc; border-radius:6px; font-size:.9rem; }
    .filters button { background:#185FA5; color:#fff; border:none; padding:6px 14px; border-radius:6px; cursor:pointer; font-size:.9rem; }
    .filters a { padding:6px 14px; border:1px solid #ccc; border-radius:6px; font-size:.9rem; text-decoration:none; color:#444; }
    .task-table { width:100%; border-collapse:collapse; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 1px 4px rgba(0,0,0,.06); }
    .task-table th { background:#1a1a2e; color:#fff; padding:10px 14px; text-align:left; font-size:.85rem; }
    .task-table td { padding:10px 14px; border-bottom:1px solid #f0f0f0; font-size:.9rem; vertical-align:middle; }
    .task-table tr:last-child td { border-bottom:none; }
    .task-table tr:hover td { background:#fafafa; }
    .badge { font-size:.75rem; padding:3px 8px; border-radius:10px; font-weight:600; }
    .badge-todo { background:#E6F1FB; color:#0C447C; }
    .badge-in_progress { background:#FAEEDA; color:#633806; }
    .badge-done { background:#EAF3DE; color:#27500A; }
    .actions { display:flex; gap:6px; align-items:center; flex-wrap:wrap; }
    .btn-edit { font-size:.8rem; padding:4px 10px; border-radius:5px; background:#f0f0f0; color:#333; text-decoration:none; border:1px solid #ddd; }
    .btn-delete { font-size:.8rem; padding:4px 10px; border-radius:5px; background:#FCEBEB; color:#791F1F; border:1px solid #f5c6c6; cursor:pointer; }
    .status-form select { font-size:.8rem; padding:3px 6px; border:1px solid #ccc; border-radius:5px; }
    .status-form button { font-size:.8rem; padding:3px 8px; background:#0F6E56; color:#fff; border:none; border-radius:5px; cursor:pointer; }
    .empty { text-align:center; padding:3rem; color:#888; }
</style>
@endsection

@section('content')

<div class="page-header">
    <h1>✅ Mes tâches</h1>
    <a href="{{ route('tasks.create') }}" class="btn-primary">+ Nouvelle tâche</a>
</div>

{{-- FILTRES --}}
<form method="GET" action="{{ route('tasks.index') }}" class="filters">
    <select name="status">
        <option value="">Tous les statuts</option>
        <option value="todo"        {{ request('status') == 'todo'        ? 'selected' : '' }}>À faire</option>
        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
        <option value="done"        {{ request('status') == 'done'        ? 'selected' : '' }}>Terminé</option>
    </select>

    <select name="category">
        <option value="">Toutes les catégories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    <button type="submit">Filtrer</button>
    <a href="{{ route('tasks.index') }}">Réinitialiser</a>
</form>

{{-- TABLEAU --}}
@if($tasks->isEmpty())
    <div class="empty">
        <p>Aucune tâche trouvée.</p>
        <a href="{{ route('tasks.create') }}" class="btn-primary" style="display:inline-block;margin-top:1rem">Créer ma première tâche</a>
    </div>
@else
    <table class="task-table">
        <thead>
            <tr>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Statut</th>
                <th>Créée le</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->category->name }}</td>
                <td>
                    <span class="badge badge-{{ $task->status }}">
                        @if($task->status == 'todo') À faire
                        @elseif($task->status == 'in_progress') En cours
                        @else Terminé
                        @endif
                    </span>
                </td>
                <td>{{ $task->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="actions">
                        {{-- Edit --}}
                        <a href="{{ route('tasks.edit', $task) }}" class="btn-edit">✏️ Modifier</a>

                        {{-- US7 — Statut rapide --}}
                        <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="status-form">
                            @csrf
                            @method('PATCH')
                            <select name="status">
                                <option value="todo"        {{ $task->status == 'todo'        ? 'selected' : '' }}>À faire</option>
                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="done"        {{ $task->status == 'done'        ? 'selected' : '' }}>Terminé</option>
                            </select>
                            <button type="submit">✓</button>
                        </form>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete"
                                onclick="return confirm('Supprimer cette tâche ?')">
                                🗑️ Supprimer
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

@endsection
