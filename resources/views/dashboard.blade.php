@extends('layouts.app')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div class="header-text">
        <h2 class="welcome-text" style="color: var(--superadmin-color); font-weight: 800; font-size: 2rem; margin-bottom: 0.25rem;">{{ __('Super Admin Portal') }}</h2>
        <p class="subtitle-text" style="color: #64748b; font-weight: 500;">{{ __('Monitor your content workflow, manage user access, and customize system settings.') }}</p>
    </div>
    <div class="header-date" style="display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.6rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.88rem; color: #64748b;">
        <i data-lucide="calendar" style="width: 16px; height: 16px; color: var(--superadmin-color);"></i>
        <span>{{ now()->format('l, d F Y') }}</span>
    </div>
</div>

<!-- Reusable Workflow Pipeline Tracker -->
@include('components.pipeline_tracker', ['roleClass' => 'super-admin', 'roleName' => 'Super Admin'])

<!-- Stats Cards -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1.25rem; margin-bottom: 2rem;">
    <!-- Total Users -->
    <div class="saas-stat-card saas-superadmin">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="users" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['total_users'] }}</h3>
        <p class="saas-stat-title">{{ __('Total Users') }}</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 75%"></div>
        </div>
    </div>

    <!-- Active Sessions -->
    <div class="saas-stat-card saas-creator" style="--creator-color: #3b82f6;">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="activity" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">4</h3>
        <p class="saas-stat-title">{{ __('Active Sessions') }}</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 90%"></div>
        </div>
    </div>

    <!-- Pending Reviews -->
    <div class="saas-stat-card saas-verifier" style="--verifier-color: #f97316;">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="clipboard-check" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['review'] }}</h3>
        <p class="saas-stat-title">{{ __('Pending Reviews') }}</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 45%"></div>
        </div>
    </div>

    <!-- Scheduled Publish -->
    <div class="saas-stat-card saas-publisher" style="--publisher-color: #8b5cf6;">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="calendar" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['approved'] }}</h3>
        <p class="saas-stat-title">{{ __('Ready to Publish') }}</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 60%"></div>
        </div>
    </div>

    <!-- System Health -->
    <div class="saas-stat-card saas-superadmin" style="--superadmin-color: #10b981; --superadmin-bg: #ecfdf5;">
        <div class="saas-stat-icon-wrapper" style="background: #ecfdf5; color: #10b981;">
            <i data-lucide="shield-check" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number" style="font-size: 1.5rem; margin-top: 0.4rem; margin-bottom: 0.4rem;">100%</h3>
        <p class="saas-stat-title">{{ __('System Health') }}</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 100%; background: #10b981;"></div>
        </div>
    </div>
</div>

<div class="dashboard-main-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
    <!-- Recent Activity -->
    <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
        <div class="activity-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">{{ __('Recent Workflow History') }}</h3>
            <a href="{{ route('archive.index') }}" style="color: var(--superadmin-color); font-size: 0.85rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">{{ __('View Full Logs') }} <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i></a>
        </div>
        
        <div class="activity-timeline" style="display: flex; flex-direction: column; gap: 1rem; position: relative;">
            <!-- Timeline Line -->
            <div style="position: absolute; left: 24px; top: 0; bottom: 0; width: 2px; background: #f1f5f9; z-index: 1;"></div>

            @forelse($recentActivities as $activity)
            <div class="activity-item" style="display: flex; align-items: flex-start; gap: 1.25rem; position: relative; z-index: 2; padding: 0.75rem; border-radius: 12px; transition: all 0.2s;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: white; border: 2px solid #f1f5f9; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    @if($activity->actor && $activity->actor->profile_photo)
                        <img src="{{ asset('uploads/avatars/' . $activity->actor->profile_photo) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="profile-avatar" style="width: 100%; height: 100%; border-radius: 0; font-size: 0.9rem; background: var(--superadmin-color); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            {{ strtoupper(substr($activity->actor->name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div style="flex: 1; background: #f8fafc; border-radius: 16px; padding: 1rem 1.5rem; border: 1px solid #f1f5f9; transition: all 0.2s;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem;">
                        <div>
                            <p style="font-size: 0.9rem; font-weight: 700; color: #1e293b; margin: 0 0 0.25rem 0;">
                                {{ $activity->actor->name ?? 'System' }} 
                                <span style="font-weight: 500; color: #64748b;">
                                    {{ $activity->new_status == 'draft' ? __('created') : __('changed status of') }} content
                                </span>
                            </p>
                            <p style="font-size: 0.8rem; font-weight: 600; color: var(--superadmin-color); display: flex; align-items: center; gap: 0.4rem; margin: 0;">
                                <i data-lucide="file-text" style="width: 14px; height: 14px;"></i> {{ $activity->content->title ?? __('Deleted Content') }}
                            </p>
                        </div>
                        <div style="text-align: right; flex-shrink: 0;">
                            <span class="badge 
                                @if($activity->new_status == 'draft') badge-gray
                                @elseif($activity->new_status == 'review') badge-orange
                                @elseif($activity->new_status == 'approved') badge-green
                                @elseif($activity->new_status == 'published') badge-blue
                                @elseif($activity->new_status == 'rejected') badge-red
                                @endif" style="font-size: 0.7rem; font-weight: 800; padding: 0.25rem 0.5rem; border-radius: 6px; text-transform: uppercase;">
                                {{ $activity->new_status }}
                            </span>
                            <p style="font-size: 0.7rem; color: #94a3b8; margin: 0.4rem 0 0 0; font-weight: 600;">
                                <i data-lucide="clock" style="width: 10px; height: 10px; display: inline-block;"></i> {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="text-align: center; padding: 3rem 1.5rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <i data-lucide="history" style="width: 40px; height: 40px; color: #94a3b8; margin-bottom: 0.75rem; display: inline-block;"></i>
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">{{ __('No history log found') }}</h4>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0;">{{ __('System operations will be logged here in chronological order.') }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions Card (Super Admin Style) -->
    <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
        <div class="activity-header" style="margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">{{ __('Quick Actions') }}</h3>
        </div>
        
        <div class="saas-actions-grid saas-superadmin" style="display: grid; grid-template-columns: 1fr; gap: 0.75rem; margin: 0;">
            <a href="{{ route('users.index') }}" class="saas-action-card">
                <div class="saas-action-icon">
                    <i data-lucide="user-plus" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>{{ __('Manage Users') }}</h4>
                <p>{{ __('Register roles, verify or block users') }}</p>
            </a>

            <a href="{{ route('settings.index') }}" class="saas-action-card">
                <div class="saas-action-icon">
                    <i data-lucide="settings" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>{{ __('System Settings') }}</h4>
                <p>{{ __('Configure storage, workflow and security') }}</p>
            </a>

            <a href="{{ route('archive.index') }}" class="saas-action-card">
                <div class="saas-action-icon">
                    <i data-lucide="activity" style="width: 16px; height: 16px;"></i>
                </div>
                <h4>{{ __('Monitor Activity Logs') }}</h4>
                <p>{{ __('Audit system and publish event traces') }}</p>
            </a>
        </div>
    </div>
</div>

<style>
    .badge-orange { background: #fff7ed; color: #f97316; }
    .badge-red { background: #fef2f2; color: #ef4444; }
    .badge-gray { background: #f1f5f9; color: #64748b; }
    .badge-green { background: #ecfdf5; color: #10b981; }
    .badge-blue { background: #eff6ff; color: #3b82f6; }
</style>
@endsection
