@extends('layouts.app')

@section('title', 'Publisher Dashboard')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div class="header-text">
        <h2 class="welcome-text" style="color: var(--publisher-color); font-weight: 800; font-size: 2rem; margin-bottom: 0.25rem;">Publisher Workspace</h2>
        <p class="subtitle-text" style="color: #64748b; font-weight: 500;">Manage your publishing workflow, release schedules, and content distribution.</p>
    </div>
    <div class="header-date" style="display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.6rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.88rem; color: #64748b;">
        <i data-lucide="calendar" style="width: 16px; height: 16px; color: var(--publisher-color);"></i>
        <span>{{ now()->format('l, d F Y') }}</span>
    </div>
</div>

<!-- Reusable Workflow Pipeline Tracker -->
@include('components.pipeline_tracker', ['roleClass' => 'publisher', 'roleName' => 'Publisher Distribution'])

<!-- Stats Cards -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2rem;">
    <!-- Ready to Publish -->
    <div class="saas-stat-card saas-superadmin">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['ready_to_publish'] }}</h3>
        <p class="saas-stat-title">Ready to Publish</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 50%"></div>
        </div>
    </div>

    <!-- Scheduled Publish -->
    <div class="saas-stat-card saas-creator" style="--creator-color: #3b82f6;">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="calendar" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['scheduled'] }}</h3>
        <p class="saas-stat-title">Scheduled Content</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 30%"></div>
        </div>
    </div>

    <!-- Published Today -->
    <div class="saas-stat-card saas-publisher">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="send" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['published_today'] }}</h3>
        <p class="saas-stat-title">Published Today</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 90%"></div>
        </div>
    </div>

    <!-- Failed Publish Card -->
    <div class="saas-stat-card saas-verifier" style="--verifier-color: #ef4444; --verifier-bg: #fef2f2;">
        <div class="saas-stat-icon-wrapper" style="background: #fef2f2; color: #ef4444;">
            <i data-lucide="alert-octagon" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">0</h3>
        <p class="saas-stat-title">Failed Publish</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 0%"></div>
        </div>
    </div>
</div>

<div class="dashboard-main-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
    <!-- Recent Publish Activity -->
    <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
        <div class="activity-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">Recent Publish History</h3>
            <a href="{{ route('publisher.logs') }}" style="color: var(--publisher-color); font-size: 0.85rem; font-weight: 700; text-decoration: none;">View All Logs</a>
        </div>
        
        <div class="activity-timeline" style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($recentActivity as $activity)
            <div class="activity-item" style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; border-radius: 12px; background: #f8fafc; border: 1px solid #f1f5f9;">
                <div class="activity-icon {{ $activity->new_status === 'published' ? 'bg-success' : 'bg-primary' }}" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white;">
                    <i data-lucide="{{ $activity->new_status === 'published' ? 'check' : 'calendar' }}" style="width: 18px; height: 18px;"></i>
                </div>
                <div class="activity-content" style="flex: 1;">
                    <div class="activity-title" style="font-size: 0.88rem; color: #1e293b; line-height: 1.5;">
                        <strong>{{ $activity->actor->name ?? 'System' }}</strong> 
                        {{ $activity->new_status === 'published' ? 'successfully published' : 'scheduled' }} 
                        <strong>"{{ Str::limit($activity->content->title ?? 'Deleted', 40) }}"</strong>
                    </div>
                    <div class="activity-time" style="font-size: 0.78rem; color: #94a3b8; margin-top: 0.25rem;">{{ $activity->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <!-- Modern Empty State -->
            <div class="empty-state" style="text-align: center; padding: 3rem 1.5rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <i data-lucide="history" style="width: 40px; height: 40px; color: #94a3b8; margin-bottom: 0.75rem; display: inline-block;"></i>
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">No publish actions</h4>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Once contents are verified, release them from the queue to list them here.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions Card (Publisher Style) -->
    <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
        <div class="activity-header" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">Quick Actions</h3>
        </div>
        
        <div class="saas-actions-grid saas-publisher" style="display: grid; grid-template-columns: 1fr; gap: 0.75rem; margin: 0;">
            <a href="{{ route('publisher.queue') }}" class="saas-action-card">
                <div class="saas-action-icon">
                    <i data-lucide="send" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>Process Publish Queue</h4>
                <p>Release or schedule pending copies</p>
            </a>

            <a href="{{ route('publisher.scheduled') }}" class="saas-action-card">
                <div class="saas-action-icon">
                    <i data-lucide="calendar" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>Manage Release Schedules</h4>
                <p>Track scheduled publishing events</p>
            </a>

            <a href="{{ route('publisher.published') }}" class="saas-action-card">
                <div class="saas-action-icon">
                    <i data-lucide="archive" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>Publishing Library</h4>
                <p>Browse full history of live content</p>
            </a>
        </div>
    </div>
</div>

<style>
    .bg-success { background: #10b981; }
    .bg-primary { background: #3b82f6; }
</style>
@endsection
