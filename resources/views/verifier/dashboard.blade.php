@extends('layouts.app')

@section('title', 'Verifier Dashboard')

@section('content')
<div class="dashboard-header" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div class="header-text">
        <h2 class="welcome-text" style="color: var(--verifier-color); font-weight: 800; font-size: 2rem; margin-bottom: 0.25rem;">Verifier Workspace</h2>
        <p class="subtitle-text" style="color: #64748b; font-weight: 500;">Quality assurance, content verification, and moderation portal.</p>
    </div>
    <div class="header-date" style="display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.6rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.88rem; color: #64748b;">
        <i data-lucide="calendar" style="width: 16px; height: 16px; color: var(--verifier-color);"></i>
        <span>{{ now()->format('l, d F Y') }}</span>
    </div>
</div>

<!-- Reusable Workflow Pipeline Tracker -->
@include('components.pipeline_tracker', ['roleClass' => 'verifier', 'roleName' => 'Verifier Curator'])

<!-- Stats Cards -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.25rem; margin-bottom: 2rem;">
    <!-- Review Queue -->
    <div class="saas-stat-card saas-creator" style="--creator-color: #3b82f6;">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="clipboard-check" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['waiting_review'] }}</h3>
        <p class="saas-stat-title">Review Queue</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 60%"></div>
        </div>
    </div>

    <!-- Approved Content -->
    <div class="saas-stat-card saas-superadmin">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['approved'] }}</h3>
        <p class="saas-stat-title">Approved Today</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 80%"></div>
        </div>
    </div>

    <!-- Rejected Content -->
    <div class="saas-stat-card saas-verifier">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="x-circle" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">{{ $stats['rejected'] }}</h3>
        <p class="saas-stat-title">Rejected Today</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 20%"></div>
        </div>
    </div>

    <!-- Avg Review Time Card -->
    <div class="saas-stat-card saas-publisher">
        <div class="saas-stat-icon-wrapper">
            <i data-lucide="zap" style="width: 20px; height: 20px;"></i>
        </div>
        <h3 class="saas-stat-number">4.2 m</h3>
        <p class="saas-stat-title">Avg Review Speed</p>
        <div class="saas-stat-progress-bar">
            <div class="saas-stat-progress-fill" style="width: 90%"></div>
        </div>
    </div>
</div>

<div class="dashboard-main-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-top: 1.5rem;">
    <!-- Review Queue Table -->
    <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
        <div class="activity-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">Pending Verification</h3>
            <a href="{{ route('reviews.index') }}" style="color: var(--verifier-color); font-size: 0.85rem; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                All Queue <i data-lucide="chevron-right" style="width: 14px; height: 14px;"></i>
            </a>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <th style="padding: 0.75rem 0.5rem; text-align: left; font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Content details</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: left; font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Type</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: left; font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Creator</th>
                        <th style="padding: 0.75rem 0.5rem; text-align: right;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviewQueue as $content)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding: 1rem 0.5rem;">
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem; margin-bottom: 0.2rem;">{{ $content->title }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8; display: flex; align-items: center; gap: 0.4rem;">
                                <i data-lucide="calendar" style="width: 12px; height: 12px;"></i> {{ $content->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td style="padding: 1rem 0.5rem;">
                            <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; padding: 0.25rem 0.5rem; background: #f1f5f9; border-radius: 6px; color: #475569;">
                                {{ $content->content_type }}
                            </span>
                        </td>
                        <td style="padding: 1rem 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 24px; height: 24px; background: var(--creator-bg); color: var(--creator-color); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">
                                    {{ strtoupper(substr($content->creator->name ?? 'U', 0, 1)) }}
                                </div>
                                <span style="font-size: 0.85rem; font-weight: 600; color: #334155;">{{ $content->creator->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td style="padding: 1rem 0.5rem; text-align: right;">
                            <a href="{{ route('reviews.index') }}" style="width: 32px; height: 32px; background: white; border: 1px solid #e2e8f0; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; color: var(--verifier-color); transition: all 0.2s;">
                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 3rem 1.5rem;">
                            <i data-lucide="check-circle" style="width: 40px; height: 40px; color: #10b981; margin-bottom: 0.75rem; display: inline-block;"></i>
                            <h4 style="font-size: 0.95rem; font-weight: 700; color: #475569; margin-bottom: 0.25rem;">All clean!</h4>
                            <p style="font-size: 0.8rem; color: #64748b; margin: 0;">No contents currently pending moderation in review queue.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Right Panel: Activity & Quick Actions -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <!-- Quick Actions Card -->
        <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
            <div class="activity-header" style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">Quick Actions</h3>
            </div>
            
            <div class="saas-actions-grid saas-verifier" style="display: grid; grid-template-columns: 1fr; gap: 0.75rem; margin: 0;">
                <a href="{{ route('reviews.index') }}" class="saas-action-card">
                    <div class="saas-action-icon">
                        <i data-lucide="clipboard-list" style="width: 16px; height: 16px;"></i>
                    </div>
                    <h4>Open Review Queue</h4>
                    <p>Curate submitted assets</p>
                </a>

                <a href="{{ route('reviews.index') }}" class="saas-action-card">
                    <div class="saas-action-icon">
                        <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                    </div>
                    <h4>Approve Pending Contents</h4>
                    <p>Approve and move to publish list</p>
                </a>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="activity-section" style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 1.5rem;">
            <div class="activity-header" style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0;">Your Moderation Logs</h3>
            </div>
            
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @forelse($recentActivity as $log)
                <div style="display: flex; gap: 0.75rem; padding: 0.75rem; border-radius: 12px; background: #f8fafc; border: 1px solid #f1f5f9;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background: {{ $log->new_status == 'approved' ? 'var(--superadmin-bg)' : '#fef2f2' }}; color: {{ $log->new_status == 'approved' ? 'var(--superadmin-color)' : '#ef4444' }}; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i data-lucide="{{ $log->new_status == 'approved' ? 'check' : 'x' }}" style="width: 14px; height: 14px;"></i>
                    </div>
                    <div style="flex: 1;">
                        <p style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin: 0;">
                            {{ ucfirst($log->new_status) }}
                        </p>
                        <p style="font-size: 0.75rem; color: #64748b; margin: 0 0 0.25rem 0;">"{{ Str::limit($log->content->title, 20) }}"</p>
                        <span style="font-size: 0.7rem; font-weight: 600; color: #94a3b8; display: flex; align-items: center; gap: 0.25rem;">
                            <i data-lucide="clock" style="width: 10px; height: 10px;"></i> {{ $log->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem 1rem; border: 1px dashed #cbd5e1; border-radius: 12px; background: #f8fafc;">
                    <p style="color: #94a3b8; font-size: 0.8rem; font-weight: 600; margin: 0;">No activities performed yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
