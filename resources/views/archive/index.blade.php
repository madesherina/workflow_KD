@extends('layouts.app')

@section('title', 'Content Archive')

@section('content')
<div class="header-section">
    <h2>Published Content Archive</h2>
    <p>Official repository of all published multimedia assets in NexPublish.</p>
</div>

<!-- Stats Section -->
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <div style="background: white; padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: #eff6ff; color: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="archive" style="width: 24px;"></i>
        </div>
        <div>
            <p style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Total Published</p>
            <h3 style="font-size: 1.5rem; font-weight: 800;">{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: #f0fdf4; color: #10b981; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="check-circle" style="width: 24px;"></i>
        </div>
        <div>
            <p style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Published Today</p>
            <h3 style="font-size: 1.5rem; font-weight: 800;">{{ $stats['today'] }}</h3>
        </div>
    </div>
    <div style="background: white; padding: 1.5rem; border-radius: 20px; border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: #fefce8; color: #ca8a04; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="calendar" style="width: 24px;"></i>
        </div>
        <div>
            <p style="font-size: 0.8rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">This Week</p>
            <h3 style="font-size: 1.5rem; font-weight: 800;">{{ $stats['week'] }}</h3>
        </div>
    </div>
</div>

<div class="card-table">
    <div class="card-table-header" style="flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 1rem; align-items: center; flex: 1;">
            <h3>Content Repository</h3>
            <form action="{{ route('archive.index') }}" method="GET" style="display: flex; gap: 0.5rem; flex: 1; max-width: 600px;">
                <div class="search-box" style="width: 100%; border: 1px solid var(--border-color);">
                    <i data-lucide="search" style="width: 16px; color: var(--text-muted);"></i>
                    <input type="text" name="search" placeholder="Search archive..." value="{{ request('search') }}">
                </div>
                <select name="type" onchange="this.form.submit()" style="width: 130px; padding: 0.5rem; border-radius: 10px; border: 1px solid var(--border-color); background: #f1f5f9; font-size: 0.85rem; font-weight: 600;">
                    <option value="">All Types</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
                    <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                    <option value="mixed" {{ request('type') == 'mixed' ? 'selected' : '' }}>Mixed</option>
                </select>
                <select name="sort" onchange="this.form.submit()" style="width: 130px; padding: 0.5rem; border-radius: 10px; border: 1px solid var(--border-color); background: #f1f5f9; font-size: 0.85rem; font-weight: 600;">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; color: #15803d; padding: 1rem; border-radius: 12px; margin: 0 1.5rem 1rem; font-size: 0.9rem; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Preview</th>
                <th>Title</th>
                <th>Type</th>
                <th>Creator</th>
                <th>Publisher</th>
                <th>Publish Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contents as $content)
            <tr>
                <td>
                    <div style="width: 60px; height: 60px; border-radius: 12px; overflow: hidden; background: #f1f5f9; border: 1px solid var(--border-color);">
                        @if($content->thumbnail)
                            <img src="{{ asset('storage/' . $content->thumbnail) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                <i data-lucide="{{ $content->content_type == 'video' ? 'video' : 'image' }}" style="width: 20px;"></i>
                            </div>
                        @endif
                    </div>
                </td>
                <td>
                    <div style="font-weight: 700; color: var(--text-main);">{{ $content->title }}</div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">{{ Str::limit($content->description, 40) }}</div>
                </td>
                <td>
                    <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted); display: flex; align-items: center; gap: 0.4rem;">
                        @if($content->content_type == 'image') <i data-lucide="image" style="width: 14px;"></i>
                        @elseif($content->content_type == 'video') <i data-lucide="video" style="width: 14px;"></i>
                        @else <i data-lucide="layers" style="width: 14px;"></i>
                        @endif
                        {{ $content->content_type }}
                    </span>
                </td>
                <td>
                    <div style="font-size: 0.85rem; font-weight: 600;">{{ $content->creator->name ?? 'Unknown' }}</div>
                </td>
                <td>
                    <div style="font-size: 0.85rem; font-weight: 600;">{{ $content->publisher->name ?? '-' }}</div>
                </td>
                <td style="color: var(--text-muted); font-size: 0.85rem;">
                    {{ $content->publish_date ? $content->publish_date->format('d M Y, H:i') : '-' }}
                </td>
                <td>
                    <span class="badge" style="background: #e0f2fe; color: #0369a1;">Published</span>
                </td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('contents.show', $content->id) }}" class="btn-icon" title="View Detail">
                            <i data-lucide="eye" style="width: 16px;"></i>
                        </a>
                        <a href="{{ asset('storage/' . ($content->copywriting_file ?? '#')) }}" class="btn-icon" title="Download Copywriting" {{ !$content->copywriting_file ? 'style=pointer-events:none;opacity:0.3' : '' }}>
                            <i data-lucide="download" style="width: 16px;"></i>
                        </a>
                        <button class="btn-icon" style="color: #64748b;" title="Archive" onclick="alert('Content Archived (Simulasi)')">
                            <i data-lucide="folder-archive" style="width: 16px;"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 5rem 2rem;">
                    <div style="color: var(--text-muted); display: flex; flex-direction: column; align-items: center; gap: 1.5rem;">
                        <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i data-lucide="layers" style="width: 40px; height: 40px; opacity: 0.5;"></i>
                        </div>
                        <div>
                            <h3 style="color: var(--text-main); margin-bottom: 0.5rem;">Archive Empty</h3>
                            <p style="font-size: 0.9rem;">Belum ada content yang dipublish.</p>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding: 1.5rem; border-top: 1px solid var(--border-color);">
        {{ $contents->links() }}
    </div>
</div>

@if($recentlyPublished->count() > 0)
<div style="margin-top: 3rem;">
    <h3 style="margin-bottom: 1.5rem;">Recently Published</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
        @foreach($recentlyPublished as $recent)
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); overflow: hidden; transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location='{{ route('contents.show', $recent->id) }}'">
            <div style="height: 160px; background: #f1f5f9; position: relative;">
                @if($recent->thumbnail)
                    <img src="{{ asset('storage/' . $recent->thumbnail) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
                <div style="position: absolute; top: 1rem; right: 1rem;">
                    <span class="badge" style="background: rgba(255,255,255,0.9); color: var(--text-main); backdrop-filter: blur(4px);">{{ ucfirst($recent->content_type) }}</span>
                </div>
            </div>
            <div style="padding: 1.5rem;">
                <h4 style="margin-bottom: 0.5rem; font-size: 1rem;">{{ Str::limit($recent->title, 40) }}</h4>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div class="avatar" style="width: 20px; height: 20px; font-size: 0.6rem;">{{ strtoupper(substr($recent->creator->name, 0, 1)) }}</div>
                        <span style="font-size: 0.75rem; color: var(--text-muted);">{{ $recent->creator->name }}</span>
                    </div>
                    <span style="font-size: 0.7rem; color: var(--text-muted);">{{ $recent->publish_date->diffForHumans() }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
