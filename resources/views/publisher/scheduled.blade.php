@extends('layouts.app')

@section('title', 'Scheduled Content')

@section('content')
<div class="content-header-modern">
    <div class="header-left">
        <h2 class="page-title">Scheduled Content</h2>
        <p class="page-subtitle">Contents set for future publication.</p>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Content Title</th>
                    <th>Creator</th>
                    <th>Scheduled Date</th>
                    <th>Time Remaining</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($queues as $queue)
                <tr>
                    <td>
                        <div class="thumbnail-preview">
                            @if($queue->content->thumbnail)
                                <img src="{{ asset('uploads/thumbnails/' . $queue->content->thumbnail) }}" alt="Thumb">
                            @else
                                <div class="thumb-placeholder">
                                    <i data-lucide="image"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="title-cell">
                            <span class="main-title">{{ $queue->content->title }}</span>
                            <span class="sub-title">{{ ucfirst($queue->content->content_type) }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="user-name">{{ $queue->content->creator->name }}</span>
                    </td>
                    <td>
                        <span class="schedule-date highlight">
                            <i data-lucide="calendar" style="width: 14px;"></i>
                            {{ $queue->scheduled_at->format('d M Y, H:i') }}
                        </span>
                    </td>
                    <td>
                        <span class="time-remaining">
                            {{ $queue->scheduled_at->diffForHumans() }}
                        </span>
                    </td>
                    <td>
                        <div class="action-flex">
                            <form action="{{ route('publisher.publish.now', $queue->content->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="icon-btn btn-publish" title="Publish Now" onclick="return confirm('Publish this content now?')">
                                    <i data-lucide="send"></i>
                                </button>
                            </form>
                            <form action="{{ route('publisher.publish.cancel', $queue->content->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="icon-btn btn-cancel" title="Cancel Schedule" onclick="return confirm('Cancel this schedule?')">
                                    <i data-lucide="x-circle"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state">
                            <i data-lucide="calendar-off" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 1rem;"></i>
                            <p>No scheduled content found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">
        {{ $queues->links() }}
    </div>
</div>

<style>
    .thumbnail-preview {
        width: 60px;
        height: 40px;
        border-radius: 6px;
        overflow: hidden;
        background: #f1f5f9;
    }
    .thumbnail-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
    }
    .title-cell {
        display: flex;
        flex-direction: column;
    }
    .main-title {
        font-weight: 700;
        color: var(--text-color);
        font-size: 0.95rem;
    }
    .sub-title {
        font-size: 0.75rem;
        color: var(--text-muted);
    }
    .schedule-date.highlight {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        color: #3b82f6;
        font-weight: 700;
        background: #eff6ff;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        width: fit-content;
    }
    .time-remaining {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }
    .action-flex {
        display: flex;
        gap: 0.5rem;
    }
    .icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-publish { background: #ecfdf5; color: #10b981; }
    .btn-publish:hover { background: #10b981; color: white; }
    
    .btn-cancel { background: #fef2f2; color: #ef4444; }
    .btn-cancel:hover { background: #ef4444; color: white; }
</style>
@endsection
