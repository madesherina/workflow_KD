@extends('layouts.app')

@section('title', 'My Content')

@section('content')
<div class="content-header-modern" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
    <div class="header-left">
        <h2 class="page-title">My Content Library</h2>
        <p class="page-subtitle">Track, edit, and manage all your digital assets and copywriting.</p>
    </div>
    <button onclick="openCreateModal()" class="icon-btn btn-view" style="width: auto; height: auto; padding: 0.6rem 1.25rem; border-radius: 10px; display: flex; align-items: center; gap: 0.5rem; background: var(--primary-green); color: white; border: none; font-weight: 600; cursor: pointer; box-shadow: 0 4px 10px rgba(34, 197, 94, 0.2);">
        <i data-lucide="plus-circle" style="width: 18px;"></i>
        <span>Create Content</span>
    </button>
</div>

<!-- Filters and Search -->
<div class="content-card" style="padding: 1.25rem; margin-bottom: 1.5rem; border-radius: 16px;">
    <form action="{{ route('creator.contents') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center;">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by content title..." style="width: 100%; padding: 0.6rem 1rem; padding-left: 2.5rem; border: 1px solid #cbd5e1; border-radius: 10px; outline: none; font-size: 0.9rem;">
            <i data-lucide="search" style="position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
        </div>
        <div style="min-width: 160px;">
            <select name="status" style="width: 100%; padding: 0.6rem 1rem; border: 1px solid #cbd5e1; border-radius: 10px; outline: none; font-size: 0.9rem; background: white;">
                <option value="">All Statuses</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="review" {{ request('status') === 'review' ? 'selected' : '' }}>Under Review</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected / Revision</option>
            </select>
        </div>
        <button type="submit" style="padding: 0.6rem 1.25rem; background: #1e293b; color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 0.4rem; font-size: 0.9rem;">
            <i data-lucide="filter" style="width: 16px;"></i> Filter
        </button>
        @if(request()->filled('search') || request()->filled('status'))
            <a href="{{ route('creator.contents') }}" style="padding: 0.6rem 1.25rem; background: #f1f5f9; color: #475569; text-decoration: none; border-radius: 10px; font-weight: 600; font-size: 0.9rem; border: 1px solid #cbd5e1;">Clear</a>
        @endif
    </form>
</div>

<!-- Alert messages -->
@if(session('success'))
    <div style="background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
        <i data-lucide="check-circle" style="width: 18px; color: #10b981;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Contents Table -->
<div class="content-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Content Details</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Date Created</th>
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
                        <span class="status-badge status-{{ $content->status }}">
                            {{ $content->status === 'review' ? 'Under Review' : ($content->status === 'rejected' ? 'Needs Revision' : ucfirst($content->status)) }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">
                            {{ $content->created_at->format('d M Y, H:i') }}
                        </span>
                    </td>
                    <td>
                        <div class="action-flex">
                            <a href="{{ route('contents.show', $content->id) }}" class="icon-btn btn-view" title="View Detail">
                                <i data-lucide="eye"></i>
                            </a>
                            @if($content->status === 'draft' || $content->status === 'rejected')
                                <a href="{{ route('creator.contents.edit', $content->id) }}" class="icon-btn btn-edit" title="Edit Content" style="background: #eff6ff; color: #3b82f6;">
                                    <i data-lucide="edit-3"></i>
                                </a>
                                <form action="{{ route('creator.contents.destroy', $content->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to permanently delete this content?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="icon-btn btn-cancel" title="Delete Content" style="border: none; background: #fef2f2; color: #ef4444; cursor: pointer;">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            @else
                                <span class="lock-indicator" title="Published or approved content is locked" style="padding: 0.4rem; background: #f1f5f9; color: #94a3b8; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px;">
                                    <i data-lucide="lock" style="width: 16px;"></i>
                                </span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state">
                            <i data-lucide="inbox" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 1rem;"></i>
                            <p>No content assets found in your library.</p>
                            <button onclick="openCreateModal()" class="btn btn-primary" style="margin-top: 1rem; border: none; background: var(--primary-green); color: white; padding: 0.5rem 1.25rem; border-radius: 8px; font-weight: 600; cursor: pointer;">Create First Asset</button>
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

<!-- Add/Create Modal -->
@include('creator.partials.create_modal')

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
        text-decoration: none;
    }
    .btn-view { background: #f8fafc; color: #64748b; }
    .btn-view:hover { background: #e2e8f0; color: #1e293b; }
    
    .status-badge {
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }
    .status-badge.status-published { background: #ecfdf5; color: #059669; }
    .status-badge.status-review { background: #fff7ed; color: #d97706; }
    .status-badge.status-rejected { background: #fef2f2; color: #dc2626; }
    .status-badge.status-approved { background: #eff6ff; color: #2563eb; }
    .status-badge.status-draft { background: #f1f5f9; color: #475569; }
</style>
@endsection
