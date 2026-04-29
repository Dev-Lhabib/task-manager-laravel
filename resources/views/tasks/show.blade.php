@extends('layouts.app')

@section('title', $task->title)

@section('styles')
<link rel="stylesheet" href="{{ asset('css/tasks.css') }}">
@endsection

@section('content')

@php
    $statusMap = [
        'todo'        => ['label' => 'À faire',      'icon' => '📋', 'step' => 1],
        'in_progress' => ['label' => 'En cours',      'icon' => '⚡', 'step' => 2],
        'in_review'   => ['label' => 'En révision',   'icon' => '🔍', 'step' => 3],
        'done'        => ['label' => 'Terminé',       'icon' => '✅', 'step' => 4],
    ];
    $currentStep = $statusMap[$task->status]['step'] ?? 1;
@endphp

{{-- Hero Banner --}}
<div class="show-hero show-hero-{{ $task->status }} animate-fadeup">
    <div class="show-hero-content">
        <a href="{{ route('tasks.index') }}" class="show-back">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
            Retour aux tâches
        </a>
        <h1 class="show-title">{{ $task->title }}</h1>
        <div class="show-meta-row">
            <span class="show-badge show-badge-{{ $task->status }}">
                {{ $statusMap[$task->status]['icon'] }}
                {{ $statusMap[$task->status]['label'] }}
            </span>
            <span class="show-meta-sep">·</span>
            <span class="show-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                {{ $task->user->name }}
            </span>
            <span class="show-meta-sep">·</span>
            <span class="show-meta-item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                {{ $task->created_at->diffForHumans() }}
            </span>
            @if($task->due_date)
                <span class="show-meta-sep">·</span>
                <span class="show-meta-item {{ $task->due_date->isPast() && $task->status !== 'done' ? 'show-meta-overdue' : '' }}">
                    📅
                    @if($task->due_date->isPast() && $task->status !== 'done')
                        En retard de {{ $task->due_date->diffForHumans() }}
                    @elseif($task->status === 'done')
                        Terminée le {{ $task->due_date->format('d/m/Y') }}
                    @else
                        Échéance {{ $task->due_date->diffForHumans() }}
                    @endif
                </span>
            @endif
        </div>
    </div>
</div>

{{-- Status Progress Tracker --}}
<div class="glass-card show-progress-card animate-fadeup animate-fadeup-1">
    <div class="progress-tracker">
        @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'in_review' => 'En révision', 'done' => 'Terminé'] as $key => $label)
            @php $step = $statusMap[$key]['step']; @endphp
            <div class="progress-step {{ $step <= $currentStep ? 'progress-step-active' : '' }} {{ $step === $currentStep ? 'progress-step-current' : '' }}">
                <div class="progress-dot">
                    @if($step < $currentStep)
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    @elseif($step === $currentStep)
                        <div class="progress-dot-pulse"></div>
                    @endif
                </div>
                <span class="progress-label">{{ $label }}</span>
            </div>
            @if(!$loop->last)
                <div class="progress-line {{ $step < $currentStep ? 'progress-line-active' : '' }}"></div>
            @endif
        @endforeach
    </div>
</div>

<div class="show-grid">

    {{-- Main Content Column --}}
    <div class="show-main-col">

        {{-- Description Card --}}
        <div class="glass-card show-content-card animate-fadeup animate-fadeup-2">
            <div class="show-section">
                <h2 class="show-section-title">
                    <span class="show-section-icon">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    </span>
                    Description
                </h2>
                <div class="show-description">
                    @if($task->description)
                        {!! nl2br(e($task->description)) !!}
                    @else
                        <div class="show-empty-state">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <p>Aucune description fournie.</p>
                            <a href="{{ route('tasks.edit', $task) }}" class="show-empty-link">Ajouter une description →</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Status Update --}}
        <div class="glass-card show-quick-status animate-fadeup animate-fadeup-3">
            <h2 class="show-section-title">
                <span class="show-section-icon icon-amber">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                </span>
                Mise à jour rapide
            </h2>
            <form method="POST" action="{{ route('tasks.updateStatus', $task) }}" class="quick-status-form">
                @csrf
                @method('PATCH')
                <div class="quick-status-options">
                    @foreach($statusMap as $key => $info)
                        <label class="quick-status-option {{ $task->status === $key ? 'quick-status-active' : '' }}">
                            <input type="radio" name="status" value="{{ $key }}" {{ $task->status === $key ? 'checked' : '' }}>
                            <span class="quick-status-dot qsd-{{ $key }}"></span>
                            <span class="quick-status-text">{{ $info['icon'] }} {{ $info['label'] }}</span>
                        </label>
                    @endforeach
                </div>
                <button type="submit" class="btn btn-accent quick-status-btn">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Mettre à jour
                </button>
            </form>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="show-sidebar animate-fadeup animate-fadeup-2">

        {{-- Details Card --}}
        <div class="glass-card show-details-card">
            <h3 class="show-details-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                Détails
            </h3>

            <div class="show-detail-row">
                <span class="show-detail-label">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="5"/><path d="M12 1v2m0 18v2m11-11h-2M3 12H1m18.07-7.07-1.41 1.41M6.34 17.66l-1.41 1.41m0-14.14 1.41 1.41m11.32 11.32 1.41 1.41"/></svg>
                    Statut
                </span>
                <span class="badge badge-{{ $task->status }}">
                    {{ $statusMap[$task->status]['label'] }}
                </span>
            </div>

            <div class="show-detail-row">
                <span class="show-detail-label">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" y1="22" x2="4" y2="15"/></svg>
                    Catégorie
                </span>
                <span class="show-detail-value show-category-tag">{{ $task->category->name }}</span>
            </div>

            <div class="show-detail-row">
                <span class="show-detail-label">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Créée le
                </span>
                <span class="show-detail-value">{{ $task->created_at->format('d/m/Y') }}</span>
            </div>

            <div class="show-detail-row">
                <span class="show-detail-label">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Modifiée
                </span>
                <span class="show-detail-value">{{ $task->updated_at->diffForHumans() }}</span>
            </div>

            @if($task->due_date)
            <div class="show-detail-row">
                <span class="show-detail-label">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    Échéance
                </span>
                <span class="show-detail-value {{ $task->due_date->isPast() && $task->status !== 'done' ? 'overdue-text' : ($task->status === 'done' ? 'done-text' : '') }}">
                    {{ $task->due_date->format('d/m/Y') }}
                </span>
            </div>
            @endif

            @if($task->due_date)
            <div class="show-due-countdown {{ $task->due_date->isPast() && $task->status !== 'done' ? 'countdown-overdue' : ($task->status === 'done' ? 'countdown-done' : 'countdown-active') }}">
                @if($task->status === 'done')
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>Tâche terminée</span>
                @elseif($task->due_date->isPast())
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span>En retard de {{ $task->due_date->diffInDays(now()) }} jour{{ $task->due_date->diffInDays(now()) > 1 ? 's' : '' }}</span>
                @else
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>{{ $task->due_date->diffInDays(now()) }} jour{{ $task->due_date->diffInDays(now()) > 1 ? 's' : '' }} restant{{ $task->due_date->diffInDays(now()) > 1 ? 's' : '' }}</span>
                @endif
            </div>
            @endif
        </div>

        {{-- Actions Card --}}
        <div class="glass-card show-actions-card">
            <h3 class="show-details-title">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                Actions
            </h3>

            <a href="{{ route('tasks.edit', $task) }}" class="show-action-btn show-action-edit">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Modifier la tâche
            </a>

            <form method="POST" action="{{ route('tasks.destroy', $task) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="show-action-btn show-action-delete"
                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
                    Supprimer la tâche
                </button>
            </form>
        </div>

    </div>

</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('.quick-status-option').forEach(function(label) {
    label.addEventListener('click', function() {
        document.querySelectorAll('.quick-status-option').forEach(function(l) {
            l.classList.remove('quick-status-active');
        });
        this.classList.add('quick-status-active');
    });
});
</script>
@endsection
