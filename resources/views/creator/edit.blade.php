@extends('layouts.app')

@section('title', 'Edit Content')

@section('content')
<div class="content-header-modern" style="margin-bottom: 1.5rem;">
    <div class="header-left">
        <h2 class="page-title">Edit Content Asset</h2>
        <p class="page-subtitle">Revise, update files, and submit your content back into the workflow.</p>
    </div>
</div>

<div class="content-card" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.05); background: white;">
    <div style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 1.5rem 2rem; display: flex; align-items: center; gap: 0.75rem;">
        <div style="width: 36px; height: 36px; background: rgba(59, 130, 246, 0.2); color: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="edit-3" style="width: 20px;"></i>
        </div>
        <div>
            <h3 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: white;">Editing: {{ $content->title }}</h3>
            <p style="margin: 0; font-size: 0.75rem; color: #94a3b8; font-weight: normal;">Owner validation: Checked & Authorized</p>
        </div>
    </div>

    <form action="{{ route('creator.contents.update', $content->id) }}" method="POST" enctype="multipart/form-data" id="editForm" style="padding: 2rem;">
        @csrf
        @method('PUT')
        <input type="hidden" name="action" id="formActionField" value="save_draft">
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div class="form-group" style="grid-column: span 2;">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Content Title <span style="color: #ef4444;">*</span></label>
                <input type="text" name="title" value="{{ old('title', $content->title) }}" class="form-control" placeholder="e.g. Summer Campaign Banner Teaser" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Description / Brief</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief outline or instructions..." style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s; resize: vertical;">{{ old('description', $content->description) }}</textarea>
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Content / Copywriting Body <span style="color: #ef4444;">*</span></label>
                <textarea name="content" class="form-control" rows="5" placeholder="Type or paste the final content text / caption / script..." required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s; resize: vertical; font-family: inherit;">{{ old('content', $content->content) }}</textarea>
            </div>

            <div class="form-group">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Content Type <span style="color: #ef4444;">*</span></label>
                <select name="content_type" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; background: white;" onchange="toggleUploadFields(this.value)">
                    <option value="image" {{ old('content_type', $content->content_type) === 'image' ? 'selected' : '' }}>Image Asset</option>
                    <option value="video" {{ old('content_type', $content->content_type) === 'video' ? 'selected' : '' }}>Video Asset</option>
                    <option value="mixed" {{ old('content_type', $content->content_type) === 'mixed' ? 'selected' : '' }}>Mixed Media Asset</option>
                </select>
            </div>

            <div class="form-group">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Thumbnail <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">(Max 2MB)</span></label>
                <input type="file" name="thumbnail" accept="image/jpg,image/jpeg,image/png,image/webp" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; margin-bottom: 0.5rem;">
                @if($content->thumbnail)
                    <div style="display: flex; align-items: center; gap: 0.5rem; background: #f8fafc; padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; width: max-content;">
                        <img src="{{ asset('storage/' . $content->thumbnail) }}" alt="Current Thumb" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;">
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Current Thumbnail</span>
                    </div>
                @endif
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Additional Images (Gallery) <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">(Optional, Multiple)</span></label>
                <input type="file" name="images[]" multiple accept="image/jpg,image/jpeg,image/png,image/webp" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; margin-bottom: 0.5rem;">
                @if(is_array($content->images))
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @foreach($content->images as $img)
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 0.25rem; background: #f8fafc; padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1;">
                                <img src="{{ asset('storage/' . $img) }}" alt="Gallery Image" style="width: 50px; height: 35px; object-fit: cover; border-radius: 4px;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Upload Fields based on Content Type Selection -->
        <div id="videoUploadWrapper" style="display: {{ old('content_type', $content->content_type) === 'video' || old('content_type', $content->content_type) === 'mixed' ? 'block' : 'none' }}; margin-bottom: 1.5rem;">
            <div class="form-group">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Video File <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">(MP4/MOV, Max 50MB)</span></label>
                <input type="file" name="video_file" accept="video/mp4,video/quicktime" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; margin-bottom: 0.5rem;">
                @if($content->video_file)
                    <div style="display: flex; align-items: center; gap: 0.5rem; background: #f8fafc; padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; width: max-content;">
                        <i data-lucide="video" style="width: 16px; color: #3b82f6;"></i>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Current Video Uploaded</span>
                    </div>
                @endif
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <div class="form-group">
                <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Copywriting Document <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">(PDF/DOCX, Max 10MB)</span></label>
                <input type="file" name="copywriting_file" accept="application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc; margin-bottom: 0.5rem;">
                @if($content->copywriting_file)
                    <div style="display: flex; align-items: center; gap: 0.5rem; background: #f8fafc; padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; width: max-content;">
                        <i data-lucide="file-text" style="width: 16px; color: #10b981;"></i>
                        <span style="font-size: 0.75rem; color: #64748b; font-weight: 600;">Current Copywriting Doc Uploaded</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rejection Note if exists -->
        @if($content->status === 'rejected' && $content->rejection_note)
            <div style="margin-bottom: 2rem; background: #fef2f2; border-left: 4px solid #ef4444; padding: 1rem 1.5rem; border-radius: 12px;">
                <h4 style="margin: 0 0 0.5rem 0; color: #b91c1c; font-weight: 700; font-size: 0.9rem; display: flex; align-items: center; gap: 0.4rem;">
                    <i data-lucide="alert-triangle" style="width: 18px;"></i> Rejection Note from Verifier
                </h4>
                <p style="margin: 0; color: #7f1d1d; font-size: 0.85rem; font-style: italic;">"{{ $content->rejection_note }}"</p>
            </div>
        @endif

        <div class="modal-footer" style="padding-top: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
            <a href="{{ route('creator.contents') }}" class="btn btn-secondary" style="padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; background: #f1f5f9; color: #475569; transition: background 0.2s; border: none; font-size: 0.9rem;">Cancel</a>
            
            <button type="submit" onclick="submitAsDraft()" class="btn btn-secondary" style="padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: 1px solid #cbd5e1; background: white; color: #475569; transition: background 0.2s; display: flex; align-items: center; gap: 0.4rem; font-size: 0.9rem;">
                <i data-lucide="save" style="width: 16px;"></i> Save Draft
            </button>
            
            <button type="submit" onclick="submitForReview()" class="btn btn-primary" style="padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; background: var(--primary-green); color: white; transition: background 0.2s; display: flex; align-items: center; gap: 0.4rem; font-size: 0.9rem;">
                <i data-lucide="send" style="width: 16px;"></i> Resubmit to Review
            </button>
        </div>
    </form>
</div>

<script>
    function submitAsDraft() {
        document.getElementById('formActionField').value = 'save_draft';
    }

    function submitForReview() {
        document.getElementById('formActionField').value = 'send_review';
    }

    function toggleUploadFields(type) {
        const videoField = document.getElementById('videoUploadWrapper');
        if (type === 'video' || type === 'mixed') {
            videoField.style.display = 'block';
        } else {
            videoField.style.display = 'none';
        }
    }
</script>
@endsection
