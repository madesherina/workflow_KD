<!-- Create Content Modal -->
<div id="createContentModal" class="modal">
    <div class="modal-content" style="max-width: 650px; width: 90%; border-radius: 20px; padding: 0; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
        <div class="modal-header" style="background: linear-gradient(135deg, #1e293b, #0f172a); color: white; padding: 1.5rem 2rem; border: none; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 36px; height: 36px; background: rgba(34, 197, 94, 0.2); color: #22c55e; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="plus-circle" style="width: 20px;"></i>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: white;">Create New Content</h3>
                    <p style="margin: 0; font-size: 0.75rem; color: #94a3b8; font-weight: normal;">Publish or draft your creative content asset</p>
                </div>
            </div>
            <button class="close-btn" onclick="closeCreateModal()" style="color: #94a3b8; transition: color 0.2s; font-size: 1.75rem; line-height: 1; border: none; background: none; cursor: pointer;">&times;</button>
        </div>
        <form action="{{ route('creator.contents.store') }}" method="POST" enctype="multipart/form-data" id="createForm" style="padding: 2rem;">
            @csrf
            <input type="hidden" name="action" id="formActionField" value="save_draft">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <div class="form-group" style="grid-column: span 2;">
                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Content Title <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Summer Campaign Banner Teaser" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s;">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Description / Brief</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Brief outline or instructions for verifier and publisher..." style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s; resize: vertical;"></textarea>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Content / Copywriting Body <span style="color: #ef4444;">*</span></label>
                    <textarea name="content" class="form-control" rows="4" placeholder="Type or paste the final content text / caption / script here..." required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; transition: border-color 0.2s; resize: vertical; font-family: inherit;"></textarea>
                </div>

                <div class="form-group">
                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Content Type <span style="color: #ef4444;">*</span></label>
                    <select name="content_type" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; background: white;" onchange="toggleUploadFields(this.value)">
                        <option value="image">Image Asset</option>
                        <option value="video">Video Asset</option>
                        <option value="mixed">Mixed Media Asset</option>
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Thumbnail / Main Image <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">(Max 2MB)</span></label>
                    <input type="file" name="thumbnail" accept="image/jpg,image/jpeg,image/png,image/webp" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc;">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Additional Images (Gallery) <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">(Optional, Multiple)</span></label>
                    <input type="file" name="images[]" multiple accept="image/jpg,image/jpeg,image/png,image/webp" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc;">
                </div>
            </div>

            <!-- Upload Fields based on Content Type Selection -->
            <div id="videoUploadWrapper" style="display: none; margin-bottom: 1.25rem;">
                <div class="form-group">
                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Video File <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">(MP4/MOV, Max 50MB)</span></label>
                    <input type="file" name="video_file" accept="video/mp4,video/quicktime" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc;">
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <div class="form-group">
                    <label style="display: block; font-weight: 700; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; color: #475569;">Copywriting Document <span style="font-size: 0.7rem; color: #64748b; font-weight: normal;">(PDF/DOCX, Max 10MB)</span></label>
                    <input type="file" name="copywriting_file" accept="application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="form-control" style="width: 100%; padding: 0.5rem; border-radius: 8px; border: 1px solid #cbd5e1; background: #f8fafc;">
                </div>
            </div>

            <div class="modal-footer" style="padding-top: 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" class="btn btn-secondary" onclick="closeCreateModal()" style="padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; background: #f1f5f9; color: #475569; transition: background 0.2s;">Cancel</button>
                <button type="submit" onclick="submitAsDraft()" class="btn btn-secondary" style="padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: 1px solid #cbd5e1; background: white; color: #475569; transition: background 0.2s; display: flex; align-items: center; gap: 0.4rem;">
                    <i data-lucide="save" style="width: 16px;"></i> Save Draft
                </button>
                <button type="submit" onclick="submitForReview()" class="btn btn-primary" style="padding: 0.7rem 1.5rem; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; background: var(--primary-green); color: white; transition: background 0.2s; display: flex; align-items: center; gap: 0.4rem;">
                    <i data-lucide="send" style="width: 16px;"></i> Send for Review
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createContentModal').style.display = 'block';
    }

    function closeCreateModal() {
        document.getElementById('createContentModal').style.display = 'none';
    }

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

    // Modal click outside to close
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('createContentModal');
        if (event.target == modal) {
            closeCreateModal();
        }
    });
</script>
