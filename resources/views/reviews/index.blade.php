@extends('layouts.app')

@section('title', 'Review Queue')

@section('content')
<div class="header-section">
    <h2>Review Queue</h2>
    <p>Monitor and verify incoming multimedia content for quality assurance.</p>
</div>

<div style="background: white; border-radius: 32px; padding: 2rem; box-shadow: 0 15px 35px -5px rgba(0,0,0,0.02); border: 1px solid #f1f5f9;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; display: flex; align-items: center; gap: 0.75rem;">
            <div style="width: 10px; height: 30px; background: #3b82f6; border-radius: 5px;"></div>
            Verification Queue
        </h3>
        
        <form action="{{ route('reviews.index') }}" method="GET" style="display: flex; gap: 0.75rem;">
            <div style="position: relative;">
                <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                <input type="text" name="search" placeholder="Search title..." value="{{ request('search') }}" style="padding: 0.6rem 1rem 0.6rem 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.85rem; width: 240px;">
            </div>
            <select name="type" onchange="this.form.submit()" style="padding: 0.6rem 1rem; border-radius: 12px; border: 1px solid #e2e8f0; background: #f8fafc; font-size: 0.85rem; font-weight: 600; width: auto;">
                <option value="">All Types</option>
                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Image</option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </form>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #dcfce7; padding: 1rem; border-radius: 16px; margin-bottom: 2rem; font-size: 0.9rem; color: #15803d; display: flex; align-items: center; gap: 0.75rem;">
            <i data-lucide="check-circle" style="width: 18px;"></i> {{ session('success') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-spacing: 0 0.75rem; border-collapse: separate;">
            <thead>
                <tr>
                    <th style="border: none; padding: 0 1rem 1rem; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Preview</th>
                    <th style="border: none; padding: 0 1rem 1rem; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Content Info</th>
                    <th style="border: none; padding: 0 1rem 1rem; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Creator</th>
                    <th style="border: none; padding: 0 1rem 1rem; font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Submitted</th>
                    <th style="border: none; padding: 0 1rem 1rem; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contents as $content)
                <tr style="background: #f8fafc; transition: all 0.2s;">
                    <td style="border: none; padding: 1.25rem 1rem; border-radius: 20px 0 0 20px;">
                        <div style="width: 60px; height: 60px; border-radius: 14px; overflow: hidden; background: white; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center;">
                            @if($content->thumbnail)
                                <img src="{{ asset('storage/' . $content->thumbnail) }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i data-lucide="{{ $content->content_type == 'video' ? 'video' : 'image' }}" style="width: 24px; color: #cbd5e1;"></i>
                            @endif
                        </div>
                    </td>
                    <td style="border: none; padding: 1.25rem 1rem;">
                        <div style="font-weight: 700; color: #1e293b; font-size: 1rem; margin-bottom: 0.25rem;">{{ $content->title }}</div>
                        <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; color: #64748b; background: white; padding: 0.25rem 0.5rem; border-radius: 6px; border: 1px solid #e2e8f0;">{{ $content->content_type }}</span>
                    </td>
                    <td style="border: none; padding: 1.25rem 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <div style="width: 28px; height: 28px; background: #3b82f6; color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800;">
                                {{ strtoupper(substr($content->creator->name ?? 'U', 0, 1)) }}
                            </div>
                            <span style="font-weight: 600; color: #475569; font-size: 0.9rem;">{{ $content->creator->name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td style="border: none; padding: 1.25rem 1rem;">
                        <span style="color: #64748b; font-size: 0.85rem; font-weight: 500;">{{ $content->created_at->format('M d, Y') }}</span>
                    </td>
                    <td style="border: none; padding: 1.25rem 1rem; border-radius: 0 20px 20px 0; text-align: right;">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="{{ route('contents.show', $content->id) }}" class="btn-icon" style="background: white; border-radius: 10px;">
                                <i data-lucide="eye" style="width: 16px;"></i>
                            </a>
                            <button onclick="openApproveModal({{ $content->id }})" class="btn-icon" style="background: #10b981; color: white; border: none; border-radius: 10px;">
                                <i data-lucide="check" style="width: 16px;"></i>
                            </button>
                            <button onclick="openRejectModal({{ $content->id }})" class="btn-icon" style="background: #ef4444; color: white; border: none; border-radius: 10px;">
                                <i data-lucide="x" style="width: 16px;"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 6rem;">
                        <div style="color: #94a3b8;">
                            <i data-lucide="inbox" style="width: 48px; height: 48px; opacity: 0.2; margin-bottom: 1rem;"></i>
                            <p style="font-weight: 600;">Queue is currently empty</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 2rem;">
        {{ $contents->links() }}
    </div>
</div>

<!-- Approve Confirmation Modal -->
<div id="approveModal" class="modal">
    <div class="modal-content" style="max-width: 400px; text-align: center; padding: 3rem 2rem;">
        <div style="width: 60px; height: 60px; background: #f0fdf4; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
            <i data-lucide="check-circle" style="width: 32px; height: 32px;"></i>
        </div>
        <h3 style="margin-bottom: 0.5rem;">Approve Content?</h3>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem;">Are you sure you want to approve this content? It will be moved to the publish queue.</p>
        <form id="approveForm" method="POST">
            @csrf
            <div style="display: flex; gap: 1rem;">
                <button type="button" class="btn-primary" style="flex: 1; background: #f1f5f9; color: #64748b;" onclick="closeModal('approveModal')">Cancel</button>
                <button type="submit" class="btn-primary" style="flex: 1;">Confirm Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content" style="max-width: 500px; padding: 2rem;">
        <div class="modal-header">
            <h3>Reject Content</h3>
        </div>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">Please provide a reason or revision instructions for the creator.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="form-row">
                <label>Rejection Note <span style="color: #ef4444;">*</span></label>
                <textarea name="rejection_note" rows="4" placeholder="Example: Thumbnail resolution is too low, please re-upload with 1080p quality." required></textarea>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn-primary" style="background: #f1f5f9; color: #64748b;" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="btn-primary" style="background: #ef4444;">Send Rejection</button>
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

    function openApproveModal(contentId) {
        document.getElementById('approveForm').action = '/review-queue/' + contentId + '/approve';
        openModal('approveModal');
    }

    function openRejectModal(contentId) {
        document.getElementById('rejectForm').action = '/review-queue/' + contentId + '/reject';
        openModal('rejectModal');
    }

    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection
