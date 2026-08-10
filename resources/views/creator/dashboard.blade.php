@extends('layouts.app')

@section('title', 'Creator Dashboard')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div class="header-text">
        <h2 class="welcome-text" style="color: var(--creator-color); font-weight: 800; font-size: 2rem; margin-bottom: 0.25rem;">Creator Workspace</h2>
        <p class="subtitle-text" style="color: #64748b; font-weight: 500;">Bring your ideas to life and track their publishing journey.</p>
    </div>
    <div class="header-date" style="display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.6rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.88rem; color: #64748b;">
        <i data-lucide="calendar" style="width: 16px; height: 16px; color: var(--creator-color);"></i>
        <span>{{ now()->format('l, d F Y') }}</span>
    </div>
</div>

<!-- Reusable Workflow Pipeline Tracker -->
@include('components.pipeline_tracker', ['roleClass' => 'creator', 'roleName' => 'Creator (Admin Junior)'])

<!-- Stats Cards -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2rem;">
    <!-- My Drafts Card -->
    <div class="saas-stat-card saas-creator">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="file-edit" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['total_draft'] }}</h3>
        <p class="saas-stat-title">My Drafts</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 40%"></div>
        </div>
    </div>

    <!-- Submitted Reviews Card -->
    <div class="saas-stat-card saas-verifier">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="clock" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['total_waiting'] }}</h3>
        <p class="saas-stat-title">Submitted Reviews</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 60%"></div>
        </div>
    </div>

    <!-- Rejected / Needs Revision Card -->
    <div class="saas-stat-card saas-publisher" style="--publisher-color: #ef4444; --publisher-bg: #fef2f2;">
        <div class="saas-stat-icon-wrapper" style="background: #fef2f2; color: #ef4444;">
            <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['total_rejected'] }}</h3>
        <p class="saas-stat-title">Rejected Content</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 25%; background: #ef4444;"></div>
        </div>
    </div>

    <!-- Published Content Card -->
    <div class="saas-stat-card saas-superadmin">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['total_published'] }}</h3>
        <p class="saas-stat-title">Published Content</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 80%"></div>
        </div>
    </div>
</div>

<div class="dashboard-main-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
    <!-- Recent Activity Timeline -->
    <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
        <div class="activity-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">My Recent Content History</h3>
            <a href="{{ route('creator.contents') }}" style="color: var(--creator-color); font-size: 0.85rem; font-weight: 700; text-decoration: none;">View My Content</a>
        </div>
        
        <div class="activity-timeline" style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($recentActivities as $activity)
            <div class="activity-item" style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; border-radius: 12px; background: #f8fafc; border: 1px solid #f1f5f9; transition: all 0.2s;">
                <div class="activity-icon {{ 
                    $activity->new_status === 'published' ? 'bg-success' : (
                    $activity->new_status === 'review' ? 'bg-warning' : (
                    $activity->new_status === 'rejected' ? 'bg-danger' : 'bg-secondary')) 
                }}" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: white;">
                    <i data-lucide="{{ 
                        $activity->new_status === 'published' ? 'check' : (
                        $activity->new_status === 'review' ? 'clock' : (
                        $activity->new_status === 'rejected' ? 'alert-circle' : 'file-text')) 
                    }}" style="width: 18px; height: 18px;"></i>
                </div>
                <div class="activity-content" style="flex: 1;">
                    <div class="activity-title" style="font-size: 0.88rem; color: #1e293b; line-height: 1.5;">
                        Content <strong>"{{ $activity->content->title ?? 'Deleted Content' }}"</strong> transitioned to 
                        <span class="status-indicator-inline {{ $activity->new_status }}" style="font-size: 0.7rem; font-weight: 800; padding: 0.15rem 0.4rem; border-radius: 4px; text-transform: uppercase;">
                            {{ strtoupper($activity->new_status) }}
                        </span>
                    </div>
                    @if($activity->note)
                        <div class="activity-note" style="font-size: 0.8rem; color: #64748b; font-style: italic; background: white; padding: 0.4rem 0.6rem; border-radius: 6px; margin: 0.3rem 0; border-left: 3px solid #cbd5e1;">"{{ $activity->note }}"</div>
                    @endif
                    <div class="activity-time" style="font-size: 0.78rem; color: #94a3b8; margin-top: 0.25rem;">{{ $activity->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <!-- Modern Empty State -->
            <div class="empty-state" style="text-align: center; padding: 3rem 1.5rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <i data-lucide="history" style="width: 40px; height: 40px; color: #94a3b8; margin-bottom: 0.75rem;"></i>
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">No recent activities</h4>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0;">Start writing copywriting content or saving drafts to populate this history log.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions Card (SaaS Card Modern Style) -->
    <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
        <div class="activity-header" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">Quick Actions</h3>
        </div>
        
        <div class="saas-actions-grid saas-creator" style="display: grid; grid-template-columns: 1fr; gap: 0.75rem; margin: 0;">
            <!-- Create Content Card -->
            <div class="saas-action-card" onclick="openCreateModal()">
                <div class="saas-action-icon">
                    <i data-lucide="plus-circle" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>Create New Content</h4>
                <p>Draft or submit copywriting asset</p>
            </div>

            <!-- Content Library Card -->
            <a href="{{ route('creator.contents') }}" class="saas-action-card">
                <div class="saas-action-icon">
                    <i data-lucide="file-text" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>Open Drafts Library</h4>
                <p>Edit copywriting and check status</p>
            </a>

            <!-- Upload Media Card -->
            <a href="{{ route('creator.contents') }}?open_upload=true" class="saas-action-card">
                <div class="saas-action-icon">
                    <i data-lucide="image" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>Upload Digital Media</h4>
                <p>Manage files, image and videos</p>
            </a>
        </div>
    </div>
</div>

<!-- Add/Create Modal -->
@include('creator.partials.create_modal')

<style>
    .bg-success { background: #10b981; }
    .bg-warning { background: #f97316; }
    .bg-danger { background: #ef4444; }
    .bg-secondary { background: #64748b; }
    .status-indicator-inline.published { background: #ecfdf5; color: #10b981; }
    .status-indicator-inline.review { background: #fff7ed; color: #f97316; }
    .status-indicator-inline.rejected { background: #fef2f2; color: #ef4444; }
    .status-indicator-inline.draft { background: #f1f5f9; color: #64748b; }
</style>
@endsection
