@extends('layouts.app')

@section('title', 'Publish Queue')

@section('content')
<div class="content-header-modern">
    <div class="header-left">
        <h2 class="page-title">Publish Queue</h2>
        <p class="page-subtitle">Contents waiting for final distribution.</p>
    </div>
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Content Details</th>
                    <th>Type</th>
                    <th>Approved By</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contents as $content)
                <tr>
                    <td>
                        <div class="thumbnail-preview">
                            @if($content->thumbnail)
                                <img src="{{ asset('uploads/thumbnails/' . $content->thumbnail) }}" alt="Thumb">
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
                            <span class="sub-title">by {{ $content->creator->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="type-badge">{{ ucfirst($content->content_type) }}</span>
                    </td>
                    <td>
                        <div class="user-info-cell">
                            <span class="user-name">{{ $content->approver->name ?? 'N/A' }}</span>
                            <span class="user-date">{{ $content->updated_at->format('d M Y') }}</span>
                        </div>
                    </td>
                    <td>
                        @php
                            $scheduled = $content->publishQueues->where('queue_status', 'scheduled')->first();
                        @endphp
                        @if($scheduled)
                            <span class="schedule-date">
                                <i data-lucide="calendar" style="width: 14px;"></i>
                                {{ $scheduled->scheduled_at->format('d M Y, H:i') }}
                            </span>
                        @else
                            <span class="text-muted" style="font-size: 0.8rem;">Not scheduled</span>
                        @endif
                    </td>
                    <td>
                        <span class="status-badge status-approved">Approved</span>
                    </td>
                    <td>
                        <div class="action-flex">
                            <a href="{{ route('contents.show', $content->id) }}" class="icon-btn btn-view" title="View Detail">
                                <i data-lucide="eye"></i>
                            </a>
                            
                            <!-- Download Options Dropdown -->
                            <div class="dropdown dropdown-container" style="display:inline-block; position:relative;">
                                <button type="button" class="icon-btn btn-download" title="Download Files" onclick="toggleDropdown('downloadDropdown{{ $content->id }}')" style="background: #f1f5f9; color: #475569; border: none;">
                                    <i data-lucide="download"></i>
                                </button>
                                <div id="downloadDropdown{{ $content->id }}" class="dropdown-menu" style="display:none; position:absolute; right:0; top:100%; background:white; border:1px solid #e2e8f0; border-radius:8px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); z-index:10; min-width: 150px; padding: 0.5rem 0;">
                                    @if($content->thumbnail)
                                        <a href="{{ route('contents.download', ['content' => $content->id, 'type' => 'thumbnail']) }}" style="display:block; padding: 0.5rem 1rem; color: #1e293b; text-decoration: none; font-size: 0.85rem;"><i data-lucide="image" style="width: 14px; margin-right: 8px;"></i> Thumbnail / Main Image</a>
                                    @endif
                                    @if(is_array($content->images))
                                        @foreach($content->images as $index => $img)
                                            <a href="{{ route('contents.download', ['content' => $content->id, 'type' => 'image', 'index' => $index]) }}" style="display:block; padding: 0.5rem 1rem; color: #1e293b; text-decoration: none; font-size: 0.85rem;"><i data-lucide="image" style="width: 14px; margin-right: 8px;"></i> Image {{ $index + 1 }}</a>
                                        @endforeach
                                    @endif
                                    @if($content->video_file)
                                        <a href="{{ route('contents.download', ['content' => $content->id, 'type' => 'video_file']) }}" style="display:block; padding: 0.5rem 1rem; color: #1e293b; text-decoration: none; font-size: 0.85rem;"><i data-lucide="video" style="width: 14px; margin-right: 8px;"></i> Video</a>
                                    @endif
                                    @if($content->copywriting_file)
                                        <a href="{{ route('contents.download', ['content' => $content->id, 'type' => 'copywriting_file']) }}" style="display:block; padding: 0.5rem 1rem; color: #1e293b; text-decoration: none; font-size: 0.85rem;"><i data-lucide="file-text" style="width: 14px; margin-right: 8px;"></i> Document</a>
                                    @endif
                                    @if($content->thumbnail || $content->video_file || $content->copywriting_file || is_array($content->images))
                                        <div style="border-top: 1px solid #e2e8f0; margin: 0.25rem 0;"></div>
                                        <a href="{{ route('contents.download_zip', $content->id) }}" style="display:block; padding: 0.5rem 1rem; color: #059669; text-decoration: none; font-size: 0.85rem; font-weight: 700;"><i data-lucide="folder-down" style="width: 14px; margin-right: 8px;"></i> Download All (ZIP)</a>
                                    @endif
                                    @if(!$content->thumbnail && empty($content->images) && !$content->video_file && !$content->copywriting_file)
                                        <span style="display:block; padding: 0.5rem 1rem; color: #94a3b8; font-size: 0.85rem;">No files available</span>
                                    @endif
                                </div>
                            </div>
                            
                            <form action="{{ route('publisher.publish.now', $content->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="icon-btn btn-publish" title="Publish Now" onclick="return confirm('Publish this content now?')">
                                    <i data-lucide="send"></i>
                                </button>
                            </form>
                            <button class="icon-btn btn-schedule" title="Schedule Publish" onclick="openScheduleModal({{ $content->id }}, '{{ $content->title }}')">
                                <i data-lucide="calendar"></i>
                            </button>
                            @if($scheduled)
                            <form action="{{ route('publisher.publish.cancel', $content->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="icon-btn btn-cancel" title="Cancel Publish" onclick="return confirm('Cancel schedule for this content?')">
                                    <i data-lucide="x-circle"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="empty-state">
                            <i data-lucide="inbox" style="width: 48px; height: 48px; color: var(--text-muted); margin-bottom: 1rem;"></i>
                            <p>No content in the publish queue.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrapper">
        {{ $contents->links() }}
    </div>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Schedule Publish</h3>
            <button class="close-btn" onclick="closeScheduleModal()">&times;</button>
        </div>
        <form id="scheduleForm" method="POST">
            @csrf
            <div class="modal-body">
                <p id="modalContentTitle" style="font-weight: 600; margin-bottom: 1rem; color: var(--primary-green);"></p>
                <div class="form-group">
                    <label for="scheduled_at">Select Date & Time</label>
                    <input type="datetime-local" id="scheduled_at" name="scheduled_at" class="form-control" required min="{{ now()->format('Y-m-d\TH:i') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeScheduleModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Schedule</button>
            </div>
        </form>
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
    }
    .user-info-cell {
        display: flex;
        flex-direction: column;
    }
    .user-name {
        font-weight: 600;
        font-size: 0.85rem;
    }
    .user-date {
        font-size: 0.7rem;
        color: var(--text-muted);
    }
    .schedule-date {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        color: #3b82f6;
        font-weight: 600;
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
    
    .btn-publish { background: #ecfdf5; color: #10b981; }
    .btn-publish:hover { background: #10b981; color: white; }
    
    .btn-schedule { background: #eff6ff; color: #3b82f6; }
    .btn-schedule:hover { background: #3b82f6; color: white; }
    
    .btn-cancel { background: #fef2f2; color: #ef4444; }
    .btn-cancel:hover { background: #ef4444; color: white; }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        backdrop-filter: blur(4px);
    }
    .modal-content {
        background: white;
        margin: 10% auto;
        width: 400px;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    .modal-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h3 { margin: 0; font-size: 1.1rem; }
    .close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-muted); }
    .modal-body { padding: 1.5rem; }
    .modal-footer {
        padding: 1.25rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }
    .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.9rem; }
    .form-control { width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 8px; }
    .btn { padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; }
    .btn-secondary { background: #f3f4f6; color: #4b5563; }
    .btn-primary { background: var(--primary-green); color: white; }
</style>

<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const isVisible = dropdown.style.display === 'block';
        
        // Hide all other dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });

        if (!isVisible) {
            dropdown.style.display = 'block';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });

    function openScheduleModal(id, title) {
        const modal = document.getElementById('scheduleModal');
        const form = document.getElementById('scheduleForm');
        const titleElem = document.getElementById('modalContentTitle');
        
        titleElem.textContent = 'Scheduling: ' + title;
        form.action = `/publisher/queue/${id}/schedule`;
        modal.style.display = 'block';
    }

    function closeScheduleModal() {
        document.getElementById('scheduleModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('scheduleModal')) {
            closeScheduleModal();
        }
    }
</script>
@endsection
