@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $user = Auth::user();
    // Single query: load all tasks with categories (avoids N+1)
    $allTasks = $user->tasks()->with('category')->latest()->get();
    $totalTasks = $allTasks->count();
    $todoCount = $allTasks->where('status', 'todo')->count();
    $progressCount = $allTasks->where('status', 'in_progress')->count();
    $reviewCount = $allTasks->where('status', 'in_review')->count();
    $doneCount = $allTasks->where('status', 'done')->count();
    $completionRate = $totalTasks > 0 ? round(($doneCount / $totalTasks) * 100) : 0;
    $recentTasks = $allTasks->take(5);
@endphp

@section('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')

{{-- Welcome Hero --}}
<div class="welcome-hero animate-fadeup">
    <h1>👋 Bonjour, {{ $user->name }} !</h1>
    <p>Voici un aperçu de ta productivité aujourd'hui.</p>
    <div class="hero-stats">
        <div class="hero-stat">
            <span class="hs-value">{{ $totalTasks }}</span>
            <span class="hs-label">Total tâches</span>
        </div>
        <div class="hero-stat">
            <span class="hs-value">{{ $completionRate }}%</span>
            <span class="hs-label">Complétion</span>
        </div>
        <div class="hero-stat">
            <span class="hs-value">{{ (int) $user->created_at->diffInDays(now()) }}</span>
            <span class="hs-label">Jours actif</span>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="glass-card stat-card stat-total animate-fadeup animate-fadeup-1">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>
        </div>
        <div class="stat-value">{{ $totalTasks }}</div>
        <div class="stat-label">Total tâches</div>
        <div class="stat-bar"></div>
    </div>
    <div class="glass-card stat-card stat-todo animate-fadeup animate-fadeup-2">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 3"/></svg>
        </div>
        <div class="stat-value">{{ $todoCount }}</div>
        <div class="stat-label">À faire</div>
        <div class="stat-bar"></div>
    </div>
    <div class="glass-card stat-card stat-progress animate-fadeup animate-fadeup-3">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48 2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48 2.83-2.83"/></svg>
        </div>
        <div class="stat-value">{{ $progressCount }}</div>
        <div class="stat-label">En cours</div>
        <div class="stat-bar"></div>
    </div>
    <div class="glass-card stat-card stat-review animate-fadeup animate-fadeup-4">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
        </div>
        <div class="stat-value">{{ $reviewCount }}</div>
        <div class="stat-label">En révision</div>
        <div class="stat-bar"></div>
    </div>
    <div class="glass-card stat-card stat-done animate-fadeup animate-fadeup-5">
        <div class="stat-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m9 12 2 2 4-4"/></svg>
        </div>
        <div class="stat-value">{{ $doneCount }}</div>
        <div class="stat-label">Terminées</div>
        <div class="stat-bar"></div>
    </div>
</div>

{{-- Main Grid --}}
<div class="dash-grid">
    {{-- Recent Tasks --}}
    <div class="glass-card section-card animate-fadeup animate-fadeup-3">
        <div class="section-header">
            <div class="section-title">
                <span class="icon">📋</span>
                Tâches récentes
            </div>
            <a href="{{ route('tasks.index') }}" class="section-link">Voir tout →</a>
        </div>

        @forelse($recentTasks as $task)
            <div class="task-item">
                <div class="task-dot dot-{{ $task->status }}"></div>
                <div class="task-info">
                    <div class="task-name">{{ $task->title }}</div>
                    <div class="task-meta">{{ $task->category->name ?? '—' }} · {{ $task->created_at->diffForHumans() }}</div>
                </div>
                <span class="badge badge-{{ $task->status }}">
                    @if($task->status == 'todo') À faire
                    @elseif($task->status == 'in_progress') En cours
                    @elseif($task->status == 'in_review') En révision
                    @else Terminé
                    @endif
                </span>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">📝</div>
                <p>Aucune tâche pour le moment.</p>
                <a href="{{ route('tasks.create') }}" class="btn btn-accent" style="margin-top:1rem;">+ Créer ma première tâche</a>
            </div>
        @endforelse
    </div>

    {{-- Progress & Profile --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        {{-- Progress Ring --}}
        <div class="glass-card section-card animate-fadeup animate-fadeup-4">
            <div class="section-header" style="margin-bottom:.75rem;">
                <div class="section-title">
                    <span class="icon">📊</span>
                    Progression
                </div>
            </div>
            <div class="progress-section">
                <div class="ring-container">
                    <svg width="140" height="140" viewBox="0 0 140 140">
                        <defs>
                            <linearGradient id="ringGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#6366f1"/>
                                <stop offset="100%" stop-color="#a855f7"/>
                            </linearGradient>
                        </defs>
                        <circle class="ring-bg" cx="70" cy="70" r="60"/>
                        <circle class="ring-fill" id="progressRing" cx="70" cy="70" r="60" data-percent="{{ $completionRate }}"/>
                    </svg>
                    <div class="ring-text">
                        <span class="ring-percent" id="ringPercent">0%</span>
                        <span class="ring-label">Complété</span>
                    </div>
                </div>
                <div class="progress-legend">
                    <div class="legend-item">
                        <div class="legend-left">
                            <div class="legend-dot" style="background:#2563eb;"></div>
                            <span class="legend-name">À faire</span>
                        </div>
                        <span class="legend-value">{{ $todoCount }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-left">
                            <div class="legend-dot" style="background:#d97706;"></div>
                            <span class="legend-name">En cours</span>
                        </div>
                        <span class="legend-value">{{ $progressCount }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-left">
                            <div class="legend-dot" style="background:#7c3aed;"></div>
                            <span class="legend-name">En révision</span>
                        </div>
                        <span class="legend-value">{{ $reviewCount }}</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-left">
                            <div class="legend-dot" style="background:#059669;"></div>
                            <span class="legend-name">Terminées</span>
                        </div>
                        <span class="legend-value">{{ $doneCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile Card --}}
        <div class="glass-card section-card animate-fadeup animate-fadeup-5">
            <div class="section-header" style="margin-bottom:.75rem;">
                <div class="section-title">
                    <span class="icon">👤</span>
                    Mon profil
                </div>
                <a href="{{ route('profile.edit') }}" class="section-link">Modifier →</a>
            </div>
            <div class="profile-summary">
                <div class="profile-avatar-lg">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
                <div class="profile-details">
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                </div>
            </div>
            <div class="profile-stats-row">
                <div class="profile-stat-item">
                    <div class="psi-value">{{ $totalTasks }}</div>
                    <div class="psi-label">Tâches</div>
                </div>
                <div class="profile-stat-item">
                    <div class="psi-value">{{ $user->created_at->format('d/m/Y') }}</div>
                    <div class="psi-label">Membre depuis</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="glass-card section-card animate-fadeup animate-fadeup-5">
    <div class="section-header">
        <div class="section-title">
            <span class="icon">⚡</span>
            Actions rapides
        </div>
    </div>
    <div class="quick-actions-grid">
        <a href="{{ route('tasks.create') }}" class="quick-action">
            <div class="qa-icon" style="background:#eef2ff;color:#6366f1;">✚</div>
            Nouvelle tâche
        </a>
        <a href="{{ route('tasks.index') }}" class="quick-action">
            <div class="qa-icon" style="background:#dbeafe;color:#2563eb;">📋</div>
            Toutes les tâches
        </a>
        <a href="{{ route('tasks.index', ['status' => 'todo']) }}" class="quick-action">
            <div class="qa-icon" style="background:#dbeafe;color:#2563eb;">🔵</div>
            À faire
        </a>
        <a href="{{ route('tasks.index', ['status' => 'in_progress']) }}" class="quick-action">
            <div class="qa-icon" style="background:#fef3c7;color:#d97706;">🟡</div>
            En cours
        </a>
        <a href="{{ route('tasks.index', ['status' => 'in_review']) }}" class="quick-action">
            <div class="qa-icon" style="background:#ede9fe;color:#7c3aed;">🟣</div>
            En révision
        </a>
        <a href="{{ route('tasks.index', ['status' => 'done']) }}" class="quick-action">
            <div class="qa-icon" style="background:#d1fae5;color:#059669;">🟢</div>
            Terminées
        </a>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
