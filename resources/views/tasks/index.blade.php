@extends('layouts.app')

@section('title', 'Mes tâches')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
@endsection

@section('content')

<div class="page-header animate-fadeup">
    <h1>
        <svg width="24" height="24" fill="none" stroke="var(--accent)" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="m9 14 2 2 4-4"/></svg>
        Mes tâches
        <span class="task-count">{{ $tasks->total() }}</span>
    </h1>
    <a href="{{ route('tasks.create') }}" class="btn btn-accent">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8m-4-4h8"/></svg>
        Nouvelle tâche
    </a>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('tasks.index') }}" class="glass-card filters animate-fadeup animate-fadeup-1">
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
            <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="filter-btn">Filtrer</button>
    <a href="{{ route('tasks.index') }}" class="filter-reset">Réinitialiser</a>
</form>

{{-- Task List --}}
@if($tasks->isEmpty())
    <div class="glass-card empty-state">
        <div class="empty-icon">📋</div>
        <h3>Aucune tâche trouvée</h3>
        <p>Commencez par créer votre première tâche pour organiser votre travail.</p>
        <a href="{{ route('tasks.create') }}" class="btn btn-accent">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v8m-4-4h8"/></svg>
            Créer ma première tâche
        </a>
    </div>
@else
    <div class="task-list">
        @foreach($tasks as $i => $task)
        <div class="glass-card task-card animate-fadeup"
        style="--delay: {{ min($i * 0.05, 0.3) }}s">
            <div class="task-status-dot td-{{ $task->status }}"></div>
            <div class="task-main">
                <a href="{{ route('tasks.show', $task) }}" class="task-link">
                    <div class="task-title">{{ $task->title }}</div>
                    <div class="task-subtitle">
                        <span>{{ $task->category->name }}</span>
                        <span class="sep">·</span>
                        <span>{{ $task->created_at->format('d/m/Y') }}</span>
                        @if($task->due_date)
                            <span class="sep">·</span>
                            <span class="{{ $task->due_date->isPast() && $task->status !== 'done' ? 'overdue-text' : '' }}">
                                📅 {{ $task->due_date->format('d/m/Y') }}{{ $task->due_date->isPast() && $task->status !== 'done' ? ' (en retard)' : '' }}
                            </span>
                        @endif
                    </div>
                </a>
            </div>
            <span class="badge badge-{{ $task->status }}">
                @if($task->status == 'todo') À faire
                @elseif($task->status == 'in_progress') En cours
                @elseif($task->status == 'in_review') En révision
                @else Terminé
                @endif
            </span>
            <div class="task-actions">
                <a href="{{ route('tasks.edit', $task) }}" class="action-btn">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Modifier
                </a>

                @if($task->status === 'done')
                    <form method="POST" action="{{ route('tasks.reopen', $task) }}" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="action-btn reopen-btn" title="Réouvrir">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                            Réouvrir
                        </button>
                    </form>
                @else
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
                @endif

                <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete-btn"
                        onclick="return confirm('Supprimer cette tâche ?')">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endif

{{-- Pagination --}}
@if($tasks->hasPages())
<div class="pagination-wrapper">
    {{ $tasks->links() }}
</div>
@endif

@endsection

