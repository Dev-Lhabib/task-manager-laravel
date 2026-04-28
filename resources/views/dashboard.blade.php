@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    .dash-header { margin-bottom: 2rem; }
    .dash-header h1 { font-size: 1.5rem; color: #1a1a2e; }
    .dash-header p { color: #666; margin-top: 4px; }
    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 2rem; }
    .stat-card { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); text-align: center; }
    .stat-card .number { font-size: 2.5rem; font-weight: 700; }
    .stat-card .label { font-size: .9rem; color: #666; margin-top: 4px; }
    .todo-color { color: #0C447C; }
    .progress-color { color: #633806; }
    .done-color { color: #27500A; }
    .quick-actions { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); margin-bottom: 2rem; }
    .quick-actions h2 { font-size: 1.1rem; margin-bottom: 1rem; color: #1a1a2e; }
    .actions-row { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-primary { background: #185FA5; color: #fff; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-size: .9rem; }
    .btn-secondary { background: #f0f0f0; color: #333; padding: 8px 18px; border-radius: 6px; text-decoration: none; font-size: .9rem; border: 1px solid #ddd; }
    .recent-tasks { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
    .recent-tasks h2 { font-size: 1.1rem; margin-bottom: 1rem; color: #1a1a2e; }
    .task-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #f0f0f0; font-size: .9rem; }
    .task-row:last-child { border-bottom: none; }
    .badge { font-size: .75rem; padding: 3px 8px; border-radius: 10px; font-weight: 600; }
    .badge-todo { background: #E6F1FB; color: #0C447C; }
    .badge-in_progress { background: #FAEEDA; color: #633806; }
    .badge-done { background: #EAF3DE; color: #27500A; }
    .profile-card { background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); margin-bottom: 2rem; }
    .profile-card h2 { font-size: 1.1rem; margin-bottom: 1rem; color: #1a1a2e; }
    .profile-info { display: flex; flex-direction: column; gap: 8px; font-size: .9rem; color: #444; }
    .profile-info span { font-weight: 600; color: #1a1a2e; }
</style>
@endsection

@section('content')

<div class="dash-header">
    <h1>👋 Bonjour, {{ Auth::user()->name }} !</h1>
    <p>Voici un aperçu de tes tâches aujourd'hui.</p>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="number todo-color">{{ Auth::user()->tasks()->where('status','todo')->count() }}</div>
        <div class="label">À faire</div>
    </div>
    <div class="stat-card">
        <div class="number progress-color">{{ Auth::user()->tasks()->where('status','in_progress')->count() }}</div>
        <div class="label">En cours</div>
    </div>
    <div class="stat-card">
        <div class="number done-color">{{ Auth::user()->tasks()->where('status','done')->count() }}</div>
        <div class="label">Terminées</div>
    </div>
</div>

{{-- Profil --}}
<div class="profile-card">
    <h2>👤 Mon profil</h2>
    <div class="profile-info">
        <div>Nom : <span>{{ Auth::user()->name }}</span></div>
        <div>Email : <span>{{ Auth::user()->email }}</span></div>
        <div>Membre depuis : <span>{{ Auth::user()->created_at->format('d/m/Y') }}</span></div>
        <div>Total tâches : <span>{{ Auth::user()->tasks()->count() }}</span></div>
    </div>
</div>

{{-- Actions rapides --}}
<div class="quick-actions">
    <h2>⚡ Actions rapides</h2>
    <div class="actions-row">
        <a href="{{ route('tasks.create') }}" class="btn-primary">+ Nouvelle tâche</a>
        <a href="{{ route('tasks.index') }}" class="btn-secondary">📋 Toutes mes tâches</a>
        <a href="{{ route('tasks.index', ['status' => 'todo']) }}" class="btn-secondary">🔵 À faire</a>
        <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" class="btn-secondary">🟡 En cours</a>
        <a href="{{ route('tasks.index', ['status' => 'done']) }}" class="btn-secondary">🟢 Terminées</a>
    </div>
</div>

{{-- 5 dernières tâches --}}
<div class="recent-tasks">
    <h2>🕐 Mes 5 dernières tâches</h2>
    @forelse(Auth::user()->tasks()->with('category')->latest()->take(5)->get() as $task)
        <div class="task-row">
            <div>
                <strong>{{ $task->title }}</strong>
                <span style="color:#888;font-size:.85rem"> — {{ $task->category->name }}</span>
            </div>
            <span class="badge badge-{{ $task->status }}">
                @if($task->status == 'todo') À faire
                @elseif($task->status == 'in_progress') En cours
                @else Terminé
                @endif
            </span>
        </div>
    @empty
        <p style="color:#888;font-size:.9rem">Aucune tâche pour le moment.</p>
    @endforelse
</div>

@endsection
