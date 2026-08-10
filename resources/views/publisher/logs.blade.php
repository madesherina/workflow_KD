@extends('layouts.app')

@section('title', 'Activity Logs')

@section('content')
<div class="content-header-modern">
    <div class="header-left">
        <h2 class="page-title">Activity Logs</h2>
        <p class="page-subtitle">Complete audit trail of publishing actions.</p>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Content</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <span class="log-time">{{ $log->created_at->format('d M Y, H:i:s') }}</span>
                    </td>
                    <td>
                        <div class="user-info-cell">
                            <span class="user-name">{{ $log->actor->name ?? 'System' }}</span>
                            <span class="user-role">{{ $log->actor->role->role_name ?? 'User' }}</span>
                        </div>
                    </td>
                    <td>
                        @php
                            $statusClass = '';
                            if ($log->new_status === 'published') $statusClass = 'status-published';
                            elseif ($log->new_status === 'approved') $statusClass = 'status-approved';
                            elseif ($log->new_status === 'rejected') $statusClass = 'status-rejected';
                            else $statusClass = 'status-pending';
                        @endphp
                        <div class="action-cell">
                            <span class="status-badge {{ $statusClass }}">{{ strtoupper($log->new_status) }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="content-title-link">{{ $log->content->title ?? 'Deleted Content' }}</span>
                    </td>
                    <td>
                        <span class="log-note">{{ $log->note }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="empty-state">
                            <i data-lucide="history" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 1rem;"></i>
                            <p>No activity logs found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">
        {{ $logs->links() }}
    </div>
</div>

<style>
    .log-time {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-family: 'Courier New', Courier, monospace;
        font-weight: 600;
    }
    .user-info-cell {
        display: flex;
        flex-direction: column;
    }
    .user-name {
        font-weight: 700;
        font-size: 0.9rem;
    }
    .user-role {
        font-size: 0.7rem;
        color: var(--primary-green);
        text-transform: uppercase;
        font-weight: 800;
    }
    .content-title-link {
        font-weight: 600;
        color: var(--text-color);
        font-size: 0.9rem;
    }
    .log-note {
        font-size: 0.85rem;
        color: #64748b;
        font-style: italic;
    }
    .status-published { background: #ecfdf5; color: #059669; }
</style>
@endsection
