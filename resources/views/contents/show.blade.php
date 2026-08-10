@extends('layouts.app')

@section('title', 'Content Detail')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <div class="header-section" style="margin-bottom: 0;">
        <a href="{{ route('contents.index') }}" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: var(--text-muted); font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem;">
            <i data-lucide="arrow-left" style="width: 16px;"></i> Back to Library
        </a>
        <h2>{{ $content->title }}</h2>
        <div style="display: flex; gap: 1rem; align-items: center; margin-top: 0.5rem;">
            <span class="badge 
                @if($content->status == 'draft') badge-gray
                @elseif($content->status == 'review') badge-blue
                @elseif($content->status == 'approved') badge-green
                @elseif($content->status == 'published') bg-blue" style="background: #e0f2fe; color: #0369a1;
                @elseif($content->status == 'rejected') badge-red" style="background: #fef2f2; color: #ef4444;
                @endif">
                {{ ucfirst($content->status) }}
            </span>
            <span style="font-size: 0.85rem; color: var(--text-muted);">
                Created by <strong>{{ $content->creator->name }}</strong> on {{ $content->created_at->format('d M Y') }}
            </span>
        </div>
    </div>

    <div style="display: flex; gap: 1rem;">
        @php
            $userRole = strtolower(Auth::user()->role->role_name ?? '');
        @endphp
        @if(($userRole == 'verifier' || $userRole == 'super admin' || $userRole == 'super_admin') && $content->status == 'review')
            <button class="btn-primary" style="background: #ef4444;" onclick="openModal('rejectModal')">Reject Content</button>
            <form action="{{ route('contents.status', $content->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="btn-primary">Approve Content</button>
            </form>
        @endif

        @if(($userRole == 'publisher' || $userRole == 'super admin' || $userRole == 'super_admin') && $content->status == 'approved')
            <form action="{{ route('contents.status', $content->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="published">
                <button type="submit" class="btn-primary" style="background: #3b82f6;">Publish Now</button>
            </form>
        @endif
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Left Column: Multimedia & Description -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Media Preview -->
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); font-weight: 800;">
                Multimedia Preview
            </div>
            <div style="padding: 2rem; background: #f8fafc; display: flex; justify-content: center; min-height: 400px; align-items: center;">
                @if($content->content_type == 'video' && $content->video_file)
                    <video controls style="max-width: 100%; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1);">
                        <source src="{{ asset('storage/' . $content->video_file) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @elseif($content->thumbnail || is_array($content->images))
                    <div style="display: flex; flex-direction: column; gap: 1rem; align-items: center; width: 100%;">
                        @if($content->thumbnail)
                            <img id="mainImagePreview" src="{{ asset('storage/' . $content->thumbnail) }}" style="max-width: 100%; max-height: 600px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); transition: opacity 0.3s ease;">
                        @endif
                        @if(is_array($content->images))
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 1rem; width: 100%; margin-top: 1rem;">
                                @foreach($content->images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" onclick="updateMainImage(this.src)" style="width: 100%; height: 100px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; transition: transform 0.2s, opacity 0.2s;" onmouseover="this.style.opacity='0.8'; this.style.transform='scale(1.05)'" onmouseout="this.style.opacity='1'; this.style.transform='scale(1)'">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @else
                    <div style="text-align: center; color: var(--text-muted);">
                        <i data-lucide="image-off" style="width: 48px; height: 48px; margin-bottom: 1rem;"></i>
                        <p>No preview available for this content.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Description & Details -->
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); padding: 2rem;">
            <h3 style="margin-bottom: 1rem;">Description</h3>
            <p style="color: var(--text-muted); line-height: 1.6; font-size: 1rem; margin-bottom: 2rem;">
                {{ $content->description ?: 'No description provided.' }}
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; border-top: 1px solid var(--border-color); padding-top: 2rem;">
                <div>
                    <h4 style="margin-bottom: 0.5rem; font-size: 0.9rem;">Copywriting Document</h4>
                    @if($content->copywriting_file)
                        <a href="{{ asset('storage/' . $content->copywriting_file) }}" target="_blank" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 12px; text-decoration: none; color: #166534; font-weight: 700;">
                            <i data-lucide="file-text"></i> Download Copywriting
                        </a>
                    @else
                        <p style="font-size: 0.85rem; color: var(--text-muted);">No document uploaded.</p>
                    @endif
                </div>
                <div>
                    <h4 style="margin-bottom: 0.5rem; font-size: 0.9rem;">Content Type</h4>
                    <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; color: var(--text-main); text-transform: capitalize;">
                        @if($content->content_type == 'image') <i data-lucide="image"></i>
                        @elseif($content->content_type == 'video') <i data-lucide="video"></i>
                        @else <i data-lucide="layers"></i>
                        @endif
                        {{ $content->content_type }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Status & History -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- System Info -->
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="margin-bottom: 1.25rem; font-size: 1.1rem;">Involved Parties</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Creator</span>
                    <span style="font-weight: 700;">{{ $content->creator->name }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 1rem; border-bottom: 1px solid #f1f5f9;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Verifier</span>
                    <span style="font-weight: 700;">{{ $content->approver->name ?? '-' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 0.85rem; color: var(--text-muted);">Publisher</span>
                    <span style="font-weight: 700;">{{ $content->publisher->name ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- History -->
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); padding: 1.5rem;">
            <h3 style="margin-bottom: 1.25rem; font-size: 1.1rem;">Activity History</h3>
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                @foreach($content->histories as $history)
                    <div style="position: relative; padding-left: 1.5rem; border-left: 2px solid #f1f5f9;">
                        <div style="position: absolute; left: -7px; top: 0; width: 12px; height: 12px; border-radius: 50%; background: var(--primary-green); border: 2px solid white;"></div>
                        <p style="font-size: 0.85rem; font-weight: 700; margin-bottom: 0.25rem;">
                            {{ ucfirst($history->new_status) }}
                        </p>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                            By {{ $history->actor->name }} • {{ $history->created_at->diffForHumans() }}
                        </p>
                        @if($history->note)
                            <div style="font-size: 0.75rem; background: #f8fafc; padding: 0.5rem; border-radius: 8px; font-style: italic; color: var(--text-muted);">
                                "{{ $history->note }}"
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Reject Content</h3>
        </div>
        <form action="{{ route('contents.status', $content->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <div class="form-row">
                <label>Rejection Note (Revision Instructions)</label>
                <textarea name="rejection_note" rows="4" style="width: 100%; padding: 1rem; border-radius: 12px; border: 1px solid var(--border-color); outline: none;" placeholder="Explain why the content is rejected and what needs to be fixed..." required></textarea>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn-primary" style="background: #f1f5f9; color: #64748b;" onclick="closeModal('rejectModal')">Cancel</button>
                <button type="submit" class="btn-primary" style="background: #ef4444;">Confirm Reject</button>
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
    function updateMainImage(newSrc) {
        const mainImg = document.getElementById('mainImagePreview');
        if (mainImg) {
            mainImg.style.opacity = 0;
            setTimeout(() => {
                mainImg.src = newSrc;
                mainImg.style.opacity = 1;
            }, 200);
        }
    }
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection
