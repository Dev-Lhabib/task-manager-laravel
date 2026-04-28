@extends('layouts.app')

@section('title', 'Toutes les tâches')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
@endsection

@section('content')

<div class="page-header animate-fadeup">
    <h1>
        <svg width="24" height="24" fill="none" stroke="var(--accent)" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8m-4-4h8"/></svg>
        Toutes les tâches
        <span class="task-count">{{ $tasks->count() }}</span>
    </h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-accent">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8m-4-4h8"/></svg>
        Nouvelle tâche
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('tasks.all') }}" class="glass-card filters animate-fadeup animate-fadeup-1">
    <svg width="18" height="18" fill="none" stroke="var(--text-muted)" stroke-width="2" viewBox="0 0 24 24"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
    <select name="status" class="filter-select">
        <option value="">Tous les statuts</option>
        <option value="todo"        {{ request('status') == 'todo'        ? 'selected' : '' }}>À faire</option>
        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
        <option value="in_review"   {{ request('status') == 'in_review'   ? 'selected' : '' }}>En révision</option>
        <option value="done"        {{ request('status') == 'done'        ? 'selected' : '' }}>Terminé</option>
    </select>

    <select name="category" class="filter-select">
        <option value="">Toutes les catégories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="filter-btn">Filtrer</button>
    <a href="{{ route('tasks.all') }}" class="filter-reset">Réinitialiser</a>
</form>

{{-- Task List --}}
@if($tasks->isEmpty())
    <div class="glass-card empty-state">
        <div class="empty-icon">📋</div>
        <h3>Aucune tâche trouvée</h3>
        <p>Il n'y a aucune tâche pour le moment.</p>
    </div>
@else
    <div class="task-list">
        @foreach($tasks as $i => $task)
        <div class="glass-card task-card animate-fadeup"
        style="--delay: {{ min($i * 0.05, 0.3) }}s">
            <div class="task-status-dot td-{{ $task->status }}"></div>
            <div class="task-main">
                <div class="task-title">{{ $task->title }}</div>
                <div class="task-subtitle">
                    <span>{{ $task->user->name }}</span>
                    <span class="sep">·</span>
                    <span>{{ $task->category->name }}</span>
                    <span class="sep">·</span>
                    <span>{{ $task->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            <span class="badge badge-{{ $task->status }}">
                @if($task->status == 'todo') À faire
                @elseif($task->status == 'in_progress') En cours
                @elseif($task->status == 'in_review') En révision
                @else Terminé
                @endif
            </span>
            @if($task->user_id == Auth::id())
            <div class="task-actions">
                <a href="{{ route('tasks.edit', $task) }}" class="action-btn">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Modifier
                </a>

                <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="status-mini">
                    @csrf
                    @method('PATCH')
                    <select name="status">
                        <option value="todo"        {{ $task->status == 'todo'        ? 'selected' : '' }}>À faire</option>
                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                        <option value="in_review"   {{ $task->status == 'in_review'   ? 'selected' : '' }}>En révision</option>
                        <option value="done"        {{ $task->status == 'done'        ? 'selected' : '' }}>Terminé</option>
                    </select>
                    <button type="submit" title="Mettre à jour">✓</button>
                </form>

                <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete-btn"
                        onclick="return confirm('Supprimer cette tâche ?')">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
@endif

@endsection
