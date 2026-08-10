@extends('layouts.app')

@section('title', 'Revision Notes')

@section('content')
<div class="content-header-modern" style="margin-bottom: 1.5rem;">
    <div class="header-left">
        <h2 class="page-title">Revision & Refinement Workspace</h2>
        <p class="page-subtitle">Track verifier feedback, address issues, and improve your content.</p>
    </div>
</div>

<div class="revisions-grid" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
    @forelse($contents as $content)
        <div class="content-card" style="border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; border: 1px solid #fee2e2; background: #fffafb; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="background: #fef2f2; border-bottom: 1px solid #fee2e2; padding: 1.25rem 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                        <i data-lucide="alert-triangle" style="width: 20px;"></i>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #991b1b;">{{ $content->title }}</h3>
                        <p style="margin: 0; font-size: 0.75rem; color: #dc2626;">Rejected • {{ $content->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <a href="{{ route('contents.show', $content->id) }}" class="icon-btn btn-view" title="View Details" style="background: white; border: 1px solid #e2e8f0; color: #475569;">
                        <i data-lucide="eye" style="width: 16px;"></i>
                    </a>
                    <a href="{{ route('creator.contents.edit', $content->id) }}" class="icon-btn btn-edit" title="Start Revision" style="background: #ef4444; color: white; display: flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; width: auto; height: auto; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 0.85rem;">
                        <i data-lucide="edit-3" style="width: 14px;"></i>
                        <span>Start Revision</span>
                    </a>
                </div>
            </div>
            
            <div style="padding: 1.5rem; display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; flex: 1;">
                <div>
                    <h5 style="margin: 0 0 0.5rem 0; font-size: 0.8rem; text-transform: uppercase; color: #64748b; font-weight: 800;">Content Brief / Body</h5>
                    <p style="margin: 0; font-size: 0.9rem; color: #334155; line-height: 1.5;">{{ Str::limit($content->content, 200) }}</p>
                </div>
                <div style="background: white; border-radius: 12px; border: 1px solid #fee2e2; padding: 1.25rem; display: flex; flex-direction: column;">
                    <h5 style="margin: 0 0 0.5rem 0; font-size: 0.8rem; text-transform: uppercase; color: #dc2626; font-weight: 800; display: flex; align-items: center; gap: 0.3rem;">
                        <i data-lucide="message-square" style="width: 14px;"></i>
                        <span>Curator Feedback</span>
                    </h5>
                    <p style="margin: 0; font-size: 0.85rem; color: #7f1d1d; font-style: italic; line-height: 1.5; flex: 1;">
                        "{{ $content->rejection_note ?? 'No detailed rejection note provided. Please check title or attachments.' }}"
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="content-card" style="padding: 4rem 2rem; text-align: center; border-radius: 16px;">
            <div class="empty-state">
                <i data-lucide="thumbs-up" style="width: 56px; height: 56px; color: var(--primary-green); margin-bottom: 1rem;"></i>
                <h3 style="font-weight: 700; color: var(--text-color);">All Clear!</h3>
                <p style="color: var(--text-muted); margin-top: 0.25rem;">You don't have any rejected content or revision requests at the moment.</p>
                <a href="{{ route('creator.contents') }}" class="btn btn-primary" style="margin-top: 1.5rem; border: none; background: var(--primary-green); color: white; padding: 0.6rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: inline-block;">Go to My Content</a>
            </div>
        </div>
    @endforelse
</div>

<div class="pagination-wrapper" style="margin-top: 1.5rem;">
    {{ $contents->links() }}
</div>

<style>
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
</style>
@endsection
