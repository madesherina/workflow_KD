@extends('layouts.app')

@section('title', 'Published Assets')

@section('content')
<div class="content-header-modern" style="margin-bottom: 1.5rem;">
    <div class="header-left">
        <h2 class="page-title">My Published Library</h2>
        <p class="page-subtitle">Your distributed digital assets that are active and live in production.</p>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Content Title</th>
                    <th>Type</th>
                    <th>Publisher</th>
                    <th>Publish Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contents as $content)
                <tr>
                    <td>
                        <div class="thumbnail-preview">
                            @if($content->thumbnail)
                                <img src="{{ asset('storage/' . $content->thumbnail) }}" alt="Thumb">
                            @else
                                <div class="thumb-placeholder">
                                    <i data-lucide="image"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="title-cell">
                            <span class="main-title">{{ $content->title }}</span>
                            <span class="sub-title">{{ Str::limit($content->description, 50) }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="type-badge">{{ ucfirst($content->content_type) }}</span>
                    </td>
                    <td>
                        <span class="publisher-name" style="font-size: 0.85rem; font-weight: 600; color: var(--text-color);">
                            {{ $content->publisher->name ?? 'System' }}
                        </span>
                    </td>
                    <td>
                        <span class="publish-date" style="display: flex; align-items: center; gap: 0.4rem; font-size: 0.85rem; font-weight: 600; color: #10b981;">
                            <i data-lucide="globe" style="width: 14px; color: #10b981;"></i>
                            {{ $content->publish_date ? $content->publish_date->format('d M Y, H:i') : 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('contents.show', $content->id) }}" class="icon-btn btn-view" title="View Detail">
                            <i data-lucide="eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state">
                            <i data-lucide="globe" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 1rem;"></i>
                            <p>No published assets yet. Submit drafts to get started!</p>
                            <a href="{{ route('creator.contents') }}" class="btn btn-primary" style="margin-top: 1rem; border: none; background: var(--primary-green); color: white; padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">Go to My Content</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper" style="padding: 1.25rem;">
        {{ $contents->links() }}
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
    .type-badge {
        padding: 0.25rem 0.75rem;
        background: #f1f5f9;
        color: #475569;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
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
        text-decoration: none;
    }
    .btn-view { background: #f8fafc; color: #64748b; }
    .btn-view:hover { background: #e2e8f0; color: #1e293b; }
</style>
@endsection
