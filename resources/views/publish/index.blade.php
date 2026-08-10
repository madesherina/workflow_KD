@extends('layouts.app')

@section('title', 'Publish Queue')

@section('content')
<div class="header-section">
    <h2>Publish Queue</h2>
    <p>Manage, schedule, and distribute approved multimedia assets to NexPublish channels.</p>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-bottom: 2rem;">
    <!-- Main Queue Table -->
    <div class="card-table" style="margin-bottom: 0;">
        <div class="card-table-header" style="flex-wrap: wrap; gap: 1rem;">
            <div style="display: flex; gap: 1rem; align-items: center; flex: 1;">
                <h3>Awaiting Publication</h3>
                <form action="{{ route('publish.index') }}" method="GET" style="display: flex; gap: 0.5rem; flex: 1; max-width: 400px;">
                    <div class="search-box" style="width: 100%; border: 1px solid var(--border-color);">
                        <i data-lucide="search" style="width: 16px; color: var(--text-muted);"></i>
                        <input type="text" name="search" placeholder="Search approved content..." value="{{ request('search') }}">
                    </div>
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
                    <th>Preview</th>
                    <th>Content Title</th>
                    <th>Type</th>
                    <th>Approved By</th>
                    <th>Schedule</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contents as $content)
                @php
                    $queue = $content->publishQueues->first();
                    $qStatus = $queue->queue_status ?? 'waiting';
                @endphp
                <tr>
                    <td>
                        <div style="width: 50px; height: 50px; border-radius: 10px; overflow: hidden; background: #f1f5f9;">
                            @if($content->thumbnail)
                                <img src="{{ asset('storage/' . $content->thumbnail) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted);">
                                    <i data-lucide="file-text" style="width: 18px;"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 700; color: var(--text-main);">{{ $content->title }}</div>
                        <span style="font-size: 0.7rem; color: var(--text-muted);">From {{ $content->creator->name }}</span>
                    </td>
                    <td>
                        <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--text-muted);">{{ $content->content_type }}</span>
                    </td>
                    <td>
                        <div style="font-size: 0.85rem; font-weight: 600;">{{ $content->approver->name ?? '-' }}</div>
                    </td>
                    <td style="font-size: 0.85rem; color: var(--text-muted);">
                        {{ $queue && $queue->scheduled_at ? $queue->scheduled_at->format('d M, H:i') : 'Immediate' }}
                    </td>
                    <td>
                        <span class="badge 
                            @if($qStatus == 'waiting') badge-gray
                            @elseif($qStatus == 'scheduled') badge-blue
                            @elseif($qStatus == 'published') badge-green
                            @elseif($qStatus == 'cancelled') badge-red
                            @endif">
                            {{ ucfirst($qStatus) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <a href="{{ route('contents.show', $content->id) }}" class="btn-icon" title="View Detail">
                                <i data-lucide="eye" style="width: 16px;"></i>
                            </a>
                            @if($qStatus != 'published')
                            <form action="{{ route('publish.now', $content->id) }}" method="POST" onsubmit="return confirm('Publish this content now?')">
                                @csrf
                                <button type="submit" class="btn-icon" style="color: #3b82f6; border-color: #dbeafe; background: #eff6ff;" title="Publish Now">
                                    <i data-lucide="rocket" style="width: 16px;"></i>
                                </button>
                            </form>
                            <button class="btn-icon" onclick="openScheduleModal({{ $content->id }})" title="Schedule">
                                <i data-lucide="calendar" style="width: 16px;"></i>
                            </button>
                            @if($qStatus == 'scheduled')
                            <form action="{{ route('publish.cancel', $content->id) }}" method="POST" onsubmit="return confirm('Cancel this schedule?')">
                                @csrf
                                <button type="submit" class="btn-icon btn-delete" title="Cancel">
                                    <i data-lucide="x-circle" style="width: 16px;"></i>
                                </button>
                            </form>
                            @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 4rem 2rem; color: var(--text-muted);">
                        <i data-lucide="inbox" style="width: 48px; height: 48px; opacity: 0.2; margin-bottom: 1rem;"></i>
                        <p>Belum ada content yang siap dipublish.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding: 1rem 1.5rem;">
            {{ $contents->links() }}
        </div>
    </div>

    <!-- Right Sidebar: Stats & Lists -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Upcoming Schedule -->
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div style="width: 36px; height: 36px; background: #fff7ed; color: #f97316; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="clock" style="width: 20px;"></i>
                </div>
                <h3 style="font-size: 1.1rem;">Upcoming Publish</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @forelse($upcoming as $item)
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="width: 44px; height: 44px; border-radius: 8px; overflow: hidden; background: #f1f5f9; flex-shrink: 0;">
                        @if($item->content->thumbnail)
                            <img src="{{ asset('storage/' . $item->content->thumbnail) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ Str::limit($item->content->title, 25) }}</div>
                        <div style="font-size: 0.75rem; color: #f97316; font-weight: 600;">{{ $item->scheduled_at->diffForHumans() }}</div>
                    </div>
                </div>
                @empty
                <p style="font-size: 0.85rem; color: var(--text-muted); text-align: center; padding: 1rem 0;">No scheduled content.</p>
                @endforelse
            </div>
        </div>

        <!-- Published Today -->
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div style="width: 36px; height: 36px; background: #f0fdf4; color: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="check-circle" style="width: 20px;"></i>
                </div>
                <h3 style="font-size: 1.1rem;">Published Today</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @forelse($publishedToday as $item)
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="width: 44px; height: 44px; border-radius: 8px; overflow: hidden; background: #f1f5f9; flex-shrink: 0;">
                        @if($item->thumbnail)
                            <img src="{{ asset('storage/' . $item->thumbnail) }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">{{ Str::limit($item->title, 25) }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $item->publish_date->format('H:i') }} • {{ $item->content_type }}</div>
                    </div>
                </div>
                @empty
                <p style="font-size: 0.85rem; color: var(--text-muted); text-align: center; padding: 1rem 0;">No content published yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Schedule Modal -->
<div id="scheduleModal" class="modal">
    <div class="modal-content" style="max-width: 450px; padding: 2.5rem;">
        <div class="modal-header">
            <h3>Schedule Content Publication</h3>
        </div>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Select a date and time for automatic publishing.</p>
        <form id="scheduleForm" method="POST">
            @csrf
            <div class="form-row">
                <label>Publish At <span style="color: #ef4444;">*</span></label>
                <input type="datetime-local" name="scheduled_at" required min="{{ now()->format('Y-m-d\TH:i') }}">
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn-primary" style="background: #f1f5f9; color: #64748b;" onclick="closeModal('scheduleModal')">Cancel</button>
                <button type="submit" class="btn-primary">Confirm Schedule</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
    }
    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }
    function openScheduleModal(contentId) {
        document.getElementById('scheduleForm').action = '/publish-queue/' + contentId + '/schedule';
        openModal('scheduleModal');
    }
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection
