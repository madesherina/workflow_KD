<!-- Modern SaaS Workflow Pipeline Tracker -->
<div class="workflow-pipeline-card">
    <div class="workflow-pipeline-header">
        <h3>
            <i data-lucide="git-branch" style="color: var(--primary-green); width: 20px; height: 20px;"></i>
            <span>NexPublish Distribution Pipeline</span>
        </h3>
        <span class="role-badge {{ $roleClass ?? 'super-admin' }}" style="display: inline-flex; align-items: center; gap: 0.4rem; font-weight: 700;">
            <i data-lucide="user-check" style="width: 14px; height: 14px;"></i>
            Active View: {{ $roleName ?? 'Super Admin' }}
        </span>
    </div>
    <div class="workflow-pipeline-steps">
        <div class="workflow-pipeline-step creator active">
            <div class="workflow-pipeline-icon">
                <i data-lucide="pen-tool" style="width: 20px; height: 20px;"></i>
            </div>
            <span class="workflow-pipeline-label">1. Creator Workspace</span>
            <span class="workflow-pipeline-desc">Drafts, copies & digital assets upload</span>
        </div>
        
        <div class="workflow-pipeline-step verifier {{ in_array($roleClass ?? '', ['verifier', 'publisher', 'super-admin']) ? 'active' : '' }}">
            <div class="workflow-pipeline-icon">
                <i data-lucide="shield-check" style="width: 20px; height: 20px;"></i>
            </div>
            <span class="workflow-pipeline-label">2. Verifier Gate</span>
            <span class="workflow-pipeline-desc">QC curation, validation & feedback loop</span>
        </div>
        
        <div class="workflow-pipeline-step publisher {{ in_array($roleClass ?? '', ['publisher', 'super-admin']) ? 'active' : '' }}">
            <div class="workflow-pipeline-icon">
                <i data-lucide="send" style="width: 20px; height: 20px;"></i>
            </div>
            <span class="workflow-pipeline-label">3. Publisher Distribution</span>
            <span class="workflow-pipeline-desc">Immediate release or scheduled campaign distribution</span>
        </div>
        
        <div class="workflow-pipeline-step live {{ ($roleClass ?? '') === 'super-admin' ? 'active' : '' }}">
            <div class="workflow-pipeline-icon">
                <i data-lucide="globe" style="width: 20px; height: 20px;"></i>
            </div>
            <span class="workflow-pipeline-label">4. Live Production</span>
            <span class="workflow-pipeline-desc">Assets deployed live and archived in history</span>
        </div>
    </div>
</div>
