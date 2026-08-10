@extends('layouts.app')

@section('title', 'Content Management')

@section('content')
<div class="header-section">
    <h2>Content Management</h2>
    <p>Professional multimedia workflow for NexPublish digital assets.</p>
</div>

<div class="card-table">
    <div class="card-table-header" style="flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 1rem; align-items: center; flex: 1;">
            <h3>Content Library</h3>
            <form action="{{ route('contents.index') }}" method="GET" style="display: flex; gap: 0.5rem; flex: 1; max-width: 500px;">
                <div class="search-box" style="width: 100%; border: 1px solid var(--border-color);">
                    <i data-lucide="search" style="width: 16px; color: var(--text-muted);"></i>
                    <input type="text" name="search" placeholder="Search title..." value="{{ request('search') }}">
                </div>
                <select name="status" onchange="this.form.submit()" style="width: 150px; padding: 0.5rem; border-radius: 10px; border: 1px solid var(--border-color); background: #f1f5f9; font-size: 0.85rem; font-weight: 600;">
                    <option value="">All Status</option>
                    @foreach(['draft', 'review', 'approved', 'published', 'rejected'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </form>
        </div>
        <button class="btn-primary" onclick="openModal('addContentModal')">
            <i data-lucide="plus" style="width: 16px;"></i> Create New Content
        </button>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; color: #15803d; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="check-circle" style="width: 18px;"></i> {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width: 80px;">Preview</th>
                <th>Title</th>
                <th>Type</th>
                <th>Status</th>
                <th>Creator</th>
                <th>Publish/Schedule</th>
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
                    <span class="badge 
                        @if($content->status == 'draft') badge-gray
                        @elseif($content->status == 'review') badge-blue
                        @elseif($content->status == 'approved') badge-green
                        @elseif($content->status == 'published') bg-blue" style="background: #e0f2fe; color: #0369a1;
                        @elseif($content->status == 'rejected') badge-red" style="background: #fef2f2; color: #ef4444;
                        @endif">
                        {{ ucfirst($content->status) }}
                    </span>
                </td>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div class="avatar" style="width: 24px; height: 24px; font-size: 0.7rem; border-radius: 6px;">
                            {{ strtoupper(substr($content->creator->name ?? 'U', 0, 1)) }}
                        </div>
                        <span style="font-size: 0.85rem; font-weight: 600;">{{ $content->creator->name ?? 'Unknown' }}</span>
                    </div>
                </td>
                <td style="color: var(--text-muted); font-size: 0.85rem;">
                    @if($content->publish_date)
                        <div style="color: #0369a1; font-weight: 700;">{{ $content->publish_date->format('d M Y') }}</div>
                        <div style="font-size: 0.7rem;">Published</div>
                    @elseif($content->scheduled_at)
                        <div style="color: #f97316; font-weight: 700;">{{ $content->scheduled_at->format('d M Y') }}</div>
                        <div style="font-size: 0.7rem;">Scheduled</div>
                    @else
                        -
                    @endif
                </td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('contents.show', $content->id) }}" class="btn-icon" title="View Detail">
                            <i data-lucide="eye" style="width: 16px;"></i>
                        </a>
                        @if($content->status == 'draft' || $content->status == 'rejected')
                            <button class="btn-icon" onclick="openEditContentModal({{ $content }})" title="Edit">
                                <i data-lucide="edit-2" style="width: 16px;"></i>
                            </button>
                        @endif
                        <form action="{{ route('contents.destroy', $content->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete" title="Delete">
                                <i data-lucide="trash-2" style="width: 16px;"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 4rem 2rem;">
                    <div style="color: var(--text-muted); display: flex; flex-direction: column; align-items: center; gap: 1rem;">
                        <i data-lucide="folder-open" style="width: 48px; height: 48px; opacity: 0.3;"></i>
                        <div>
                            <h4 style="color: var(--text-main);">No Content Found</h4>
                            <p style="font-size: 0.85rem;">Mungkin data yang kamu tambahkan manual tidak memiliki relasi yang tepat. Coba buat baru melalui tombol di atas!</p>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Dynamic Add Content Modal -->
<div id="addContentModal" class="modal">
    <div class="modal-content" style="max-width: 800px; padding: 0; overflow: hidden;">
        <div style="padding: 1.5rem 2rem; background: var(--light-bg); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 800;">Create Multimedia Content</h3>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">New Workflow Instance</p>
            </div>
            <div class="status-indicator active">Draft</div>
        </div>

        <form action="{{ route('contents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="action" id="add_form_action" value="save_draft">
            
            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 2rem;">
                <!-- Left Section: Text Content -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-row">
                        <label>Content Title <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="title" placeholder="Ex: NexPublish Launch Campaign 2026" required>
                    </div>

                    <div class="form-row">
                        <label>Short Description</label>
                        <textarea name="description" rows="2" placeholder="Brief summary of this content..."></textarea>
                    </div>

                    <div class="form-row">
                        <label>Copywriting / Caption Text</label>
                        <textarea name="content" rows="6" placeholder="Write your captions, hashtags, and CTA here..."></textarea>
                    </div>
                </div>

                <!-- Right Section: Media & Workflow -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="form-row">
                        <label>Content Type <span style="color: #ef4444;">*</span></label>
                        <select name="content_type" id="content_type_select" onchange="toggleFormFields(this.value)" required>
                            <option value="image">Image Only</option>
                            <option value="video">Video Only</option>
                            <option value="mixed">Mixed Media</option>
                        </select>
                    </div>

                    <!-- Dynamic Upload Fields -->
                    <div id="thumbnail_upload_field">
                        <label>Thumbnail / Main Image Preview</label>
                        <div class="upload-box" onclick="document.getElementById('thumb_input').click()">
                            <i data-lucide="image"></i>
                            <p>Click to upload JPG, PNG, WEBP</p>
                            <input type="file" id="thumb_input" name="thumbnail" accept="image/*" style="display:none" onchange="previewImage(this, 'thumb_preview')">
                            <div id="thumb_preview" class="preview-container">
                                <img src="">
                            </div>
                        </div>
                    </div>

                    <div id="images_upload_field" style="margin-top: 1rem;">
                        <label>Additional Images (Gallery)</label>
                        <div class="upload-box" onclick="document.getElementById('images_input').click()">
                            <i data-lucide="images"></i>
                            <p>Upload multiple images</p>
                            <input type="file" id="images_input" name="images[]" accept="image/*" multiple style="display:none" onchange="showFileInfo(this, 'images_info')">
                            <div id="images_info" class="file-info" style="display:none">
                                <span class="file-name">Multiple files selected</span>
                            </div>
                        </div>
                    </div>

                    <div id="video_upload_field" style="display:none">
                        <label>Video Content (MP4/MOV)</label>
                        <div class="upload-box" onclick="document.getElementById('video_input').click()">
                            <i data-lucide="video"></i>
                            <p>Upload video file (max 50MB)</p>
                            <input type="file" id="video_input" name="video_file" accept="video/*" style="display:none" onchange="showFileInfo(this, 'video_info')">
                            <div id="video_info" class="file-info" style="display:none">
                                <i data-lucide="file-video" style="color: var(--primary-green); width: 16px;"></i>
                                <span class="file-name">filename.mp4</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Copywriting Document (Optional)</label>
                        <div class="upload-box" style="padding: 1rem;" onclick="document.getElementById('doc_input').click()">
                            <div style="display: flex; align-items: center; justify-content: center; gap: 0.5rem;">
                                <i data-lucide="file-text" style="margin:0; width: 20px;"></i>
                                <p style="margin:0">PDF or DOCX</p>
                            </div>
                            <input type="file" id="doc_input" name="copywriting_file" accept=".pdf,.docx" style="display:none" onchange="showFileInfo(this, 'doc_info')">
                            <div id="doc_info" class="file-info" style="display:none; margin-top: 0.5rem;">
                                <span class="file-name">doc.pdf</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <label>Schedule Publish Date</label>
                        <input type="datetime-local" name="scheduled_at">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                <button type="button" class="btn-primary" style="background: #f1f5f9; color: #64748b;" onclick="closeModal('addContentModal')">Cancel</button>
                <button type="submit" class="btn-primary" style="background: #94a3b8;" onclick="setFormAction('add_form_action', 'save_draft')">Save Draft</button>
                <button type="submit" class="btn-primary" onclick="setFormAction('add_form_action', 'send_review')">
                    <i data-lucide="send" style="width: 16px;"></i> Send To Review
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Content Modal (Simplified for logic) -->
<div id="editContentModal" class="modal">
    <div class="modal-content" style="max-width: 800px; padding: 2rem;">
        <div class="modal-header">
            <h3>Edit Multimedia Content</h3>
        </div>
        <form id="editContentForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Reusing similar structure but mapping data via JS -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <div class="form-row">
                        <label>Title</label>
                        <input type="text" name="title" id="edit_title" required>
                    </div>
                    <div class="form-row">
                        <label>Description</label>
                        <textarea name="description" id="edit_description" rows="2"></textarea>
                    </div>
                    <div class="form-row">
                        <label>Copywriting Text</label>
                        <textarea name="content" id="edit_content_text" rows="5"></textarea>
                    </div>
                </div>
                <div>
                    <div class="form-row">
                        <label>Content Type</label>
                        <select name="content_type" id="edit_content_type" onchange="toggleFormFields(this.value, 'edit_')" required>
                            <option value="image">Image Only</option>
                            <option value="video">Video Only</option>
                            <option value="mixed">Mixed Media</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label>Thumbnail / Main Image</label>
                        <input type="file" name="thumbnail" accept="image/*">
                    </div>
                    <div class="form-row">
                        <label>Additional Images (Gallery)</label>
                        <input type="file" name="images[]" accept="image/*" multiple>
                    </div>
                    <div class="form-row" id="edit_video_field">
                        <label>Video File</label>
                        <input type="file" name="video_file" accept="video/*">
                    </div>
                    <div class="form-row">
                        <label>Schedule Date</label>
                        <input type="datetime-local" name="scheduled_at" id="edit_scheduled_at">
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn-primary" style="background: #f1f5f9; color: #64748b;" onclick="closeModal('editContentModal')">Cancel</button>
                <button type="submit" class="btn-primary">Update Content</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) {
        document.getElementById(id).style.display = 'block';
        lucide.createIcons();
    }

    function closeModal(id) {
        document.getElementById(id).style.display = 'none';
    }

    function setFormAction(inputId, value) {
        document.getElementById(inputId).value = value;
    }

    function toggleFormFields(type, prefix = '') {
        const videoField = document.getElementById(prefix + 'video_upload_field') || document.getElementById(prefix + 'video_field');
        const thumbField = document.getElementById(prefix + 'thumbnail_upload_field');
        const imagesField = document.getElementById(prefix + 'images_upload_field');

        if (type === 'image') {
            if(videoField) videoField.style.display = 'none';
            if(thumbField) thumbField.style.display = 'block';
            if(imagesField) imagesField.style.display = 'block';
        } else if (type === 'video') {
            if(videoField) videoField.style.display = 'block';
            if(thumbField) thumbField.style.display = 'block';
            if(imagesField) imagesField.style.display = 'block';
        } else {
            if(videoField) videoField.style.display = 'block';
            if(thumbField) thumbField.style.display = 'block';
            if(imagesField) imagesField.style.display = 'block';
        }
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const img = preview.querySelector('img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function showFileInfo(input, infoId) {
        const info = document.getElementById(infoId);
        const name = info.querySelector('.file-name');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const size = (file.size / (1024 * 1024)).toFixed(2);
            name.textContent = `${file.name} (${size} MB)`;
            info.style.display = 'flex';
        }
    }

    function openEditContentModal(content) {
        document.getElementById('edit_title').value = content.title;
        document.getElementById('edit_description').value = content.description;
        document.getElementById('edit_content_text').value = content.content;
        document.getElementById('edit_content_type').value = content.content_type;
        
        if (content.scheduled_at) {
            // Convert to local datetime string for input
            const date = new Date(content.scheduled_at);
            const offset = date.getTimezoneOffset() * 60000;
            const localISOTime = (new Date(date - offset)).toISOString().slice(0, 16);
            document.getElementById('edit_scheduled_at').value = localISOTime;
        }

        toggleFormFields(content.content_type, 'edit_');
        document.getElementById('editContentForm').action = '/contents/' + content.id;
        openModal('editContentModal');
    }

    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection
