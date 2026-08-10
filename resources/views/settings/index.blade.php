@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="header-section">
    <h2>{{ __('System Settings') }}</h2>
    <p>{{ __('Manage global application configurations, workflow rules, and security policies.') }}</p>
</div>

<div class="settings-container" style="display: grid; grid-template-columns: 280px 1fr; gap: 2rem; align-items: start;">
    <!-- Settings Navigation -->
    <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); padding: 1.5rem; position: sticky; top: 100px;">
        <div style="display: flex; flex-direction: column; gap: 0.5rem;" id="settingsTabs">
            <button class="tab-btn active" onclick="showSection('general')">
                <i data-lucide="settings"></i> {{ __('General Settings') }}
            </button>
            <button class="tab-btn" onclick="showSection('workflow')">
                <i data-lucide="git-pull-request"></i> {{ __('Workflow Settings') }}
            </button>
            <button class="tab-btn" onclick="showSection('notification')">
                <i data-lucide="bell"></i> {{ __('Notification Settings') }}
            </button>
            <button class="tab-btn" onclick="showSection('security')">
                <i data-lucide="shield-check"></i> {{ __('Security Settings') }}
            </button>
            <button class="tab-btn" onclick="showSection('storage')">
                <i data-lucide="hard-drive"></i> {{ __('Storage Settings') }}
            </button>
        </div>
    </div>

    <!-- Settings Content -->
    <div id="settingsContent">
        <!-- Toast Notification Container -->
        <div id="toast" style="display: none; align-items: center; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-size: 0.85rem; font-weight: 600; animation: fadeIn 0.4s ease;">
            <i id="toastIcon" style="width: 18px; flex-shrink: 0;"></i>
            <span id="toastMessage"></span>
        </div>

        <!-- General Settings -->
        <section id="general" class="settings-section">
            <div class="card-table" style="margin-bottom: 0; padding: 2rem;">
                <h3 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="settings" style="color: var(--primary-green);"></i> {{ __('General Application Settings') }}
                </h3>
                <form class="settings-form" data-section="general">
                    @csrf
                    <input type="hidden" name="section" value="general">
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="form-row">
                            <label>{{ __('Application Name') }}</label>
                            <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'NexPublish Multimedia Workflow' }}" placeholder="{{ __('Enter app name') }}">
                        </div>
                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label>{{ __('Timezone') }}</label>
                                <select name="timezone">
                                    <option value="Asia/Jakarta" {{ ($settings['timezone'] ?? '') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (GMT+7)</option>
                                    <option value="UTC" {{ ($settings['timezone'] ?? '') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                </select>
                            </div>
                            <div>
                                <label>{{ __('System Language') }}</label>
                                <select name="language">
                                    <option value="en" {{ ($settings['language'] ?? '') == 'en' ? 'selected' : '' }}>English (US)</option>
                                    <option value="id" {{ ($settings['language'] ?? '') == 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <label>{{ __('Application Logo') }}</label>
                            <div style="display: flex; align-items: center; gap: 1.5rem; padding: 1rem; border: 1px dashed var(--border-color); border-radius: 12px;">
                                <div style="width: 60px; height: 60px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; font-weight: 800; color: var(--primary-green);" id="logoPreviewContainer">
                                    @if(isset($settings['app_logo']) && $settings['app_logo'])
                                        <img src="{{ asset('uploads/system/' . $settings['app_logo']) }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;" id="logoPreviewImg">
                                    @else
                                        <span id="logoInitials">NEX</span>
                                        <img src="" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; display: none;" id="logoPreviewImg">
                                    @endif
                                </div>
                                <div>
                                    <input type="file" name="logo" id="logoInput" accept="image/*" style="display: none;">
                                    <label for="logoInput" class="btn-primary" style="font-size: 0.8rem; padding: 0.5rem 1rem; cursor: pointer; display: inline-block;">{{ __('Change Logo') }}</label>
                                </div>
                                <p style="font-size: 0.75rem; color: var(--text-muted);">{{ __('Recommended size 512x512px. Max 2MB.') }}</p>
                            </div>
                        </div>
                    </div>
                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary save-btn" style="padding: 0.8rem 2rem;">
                            <span class="btn-text">{{ __('Save Changes') }}</span>
                            <i data-lucide="loader-2" class="spinner" style="display: none; animation: spin 1s linear infinite;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Workflow Settings -->
        <section id="workflow" class="settings-section" style="display: none;">
            <div class="card-table" style="margin-bottom: 0; padding: 2rem;">
                <h3 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="git-pull-request" style="color: var(--primary-green);"></i> {{ __('Content Workflow Rules') }}
                </h3>
                <form class="settings-form" data-section="workflow">
                    @csrf
                    <input type="hidden" name="section" value="workflow">
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8fafc; border-radius: 16px;">
                            <div>
                                <p style="font-weight: 700; font-size: 0.95rem;">{{ __('Mandatory Review Process') }}</p>
                                <p style="font-size: 0.8rem; color: var(--text-muted);">{{ __('Require verifier approval before any content can be published.') }}</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="mandatory_review" value="1" {{ ($settings['mandatory_review'] ?? '1') == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8fafc; border-radius: 16px;">
                            <div>
                                <p style="font-weight: 700; font-size: 0.95rem;">{{ __('Auto-Publish Approved Content') }}</p>
                                <p style="font-size: 0.8rem; color: var(--text-muted);">{{ __('Automatically move approved content to published state if no schedule is set.') }}</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="auto_publish" value="1" {{ ($settings['auto_publish'] ?? '0') == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-row">
                            <label>{{ __('Default Rejection Retention') }}</label>
                            <select name="rejection_retention">
                                <option value="30" {{ ($settings['rejection_retention'] ?? '') == '30' ? 'selected' : '' }}>{{ __('30 Days') }}</option>
                                <option value="60" {{ ($settings['rejection_retention'] ?? '') == '60' ? 'selected' : '' }}>{{ __('60 Days') }}</option>
                                <option value="unlimited" {{ ($settings['rejection_retention'] ?? '') == 'unlimited' ? 'selected' : '' }}>{{ __('Unlimited') }}</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary save-btn" style="padding: 0.8rem 2rem;">
                            <span class="btn-text">{{ __('Save Changes') }}</span>
                            <i data-lucide="loader-2" class="spinner" style="display: none; animation: spin 1s linear infinite;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Notification Settings -->
        <section id="notification" class="settings-section" style="display: none;">
            <div class="card-table" style="margin-bottom: 0; padding: 2rem;">
                <h3 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="bell" style="color: var(--primary-green);"></i> System Notifications & Alerts
                </h3>
                <form class="settings-form" data-section="notification">
                    @csrf
                    <input type="hidden" name="section" value="notification">
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div class="form-row">
                            <label>System Email Address</label>
                            <input type="email" name="system_email" value="{{ $settings['system_email'] ?? 'noreply@nexpublish.com' }}">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="font-weight: 600; font-size: 0.9rem;">Email Notifications for New Content</span>
                            <label class="switch">
                                <input type="checkbox" name="email_notif_new_content" value="1" {{ ($settings['email_notif_new_content'] ?? '1') == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="font-weight: 600; font-size: 0.9rem;">Review Reminder Alerts (Hourly)</span>
                            <label class="switch">
                                <input type="checkbox" name="review_reminder_alerts" value="1" {{ ($settings['review_reminder_alerts'] ?? '1') == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 0; border-bottom: 1px solid #f1f5f9;">
                            <span style="font-weight: 600; font-size: 0.9rem;">Push Notifications on Desktop</span>
                            <label class="switch">
                                <input type="checkbox" name="push_notifications" value="1" {{ ($settings['push_notifications'] ?? '0') == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary save-btn" style="padding: 0.8rem 2rem;">
                            <span class="btn-text">{{ __('Save Changes') }}</span>
                            <i data-lucide="loader-2" class="spinner" style="display: none; animation: spin 1s linear infinite;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Security Settings -->
        <section id="security" class="settings-section" style="display: none;">
            <div class="card-table" style="margin-bottom: 0; padding: 2rem;">
                <h3 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="shield-check" style="color: var(--primary-green);"></i> Security & Privacy
                </h3>
                <form class="settings-form" data-section="security">
                    @csrf
                    <input type="hidden" name="section" value="security">
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div class="form-row">
                            <label>Session Timeout (Minutes)</label>
                            <input type="number" name="session_timeout" value="{{ $settings['session_timeout'] ?? '120' }}">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: #f8fafc; border-radius: 16px;">
                            <div>
                                <p style="font-weight: 700; font-size: 0.95rem;">Two-Factor Authentication (2FA)</p>
                                <p style="font-size: 0.8rem; color: var(--text-muted);">Require a security code for all Super Admin logins.</p>
                            </div>
                            <label class="switch">
                                <input type="checkbox" name="require_2fa" value="1" {{ ($settings['require_2fa'] ?? '0') == '1' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="form-row">
                            <label>Activity Log Retention</label>
                            <select name="log_retention">
                                <option value="90" {{ ($settings['log_retention'] ?? '') == '90' ? 'selected' : '' }}>90 Days</option>
                                <option value="365" {{ ($settings['log_retention'] ?? '') == '365' ? 'selected' : '' }}>1 Year</option>
                                <option value="forever" {{ ($settings['log_retention'] ?? '') == 'forever' ? 'selected' : '' }}>Forever</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary save-btn" style="padding: 0.8rem 2rem;">
                            <span class="btn-text">{{ __('Save Changes') }}</span>
                            <i data-lucide="loader-2" class="spinner" style="display: none; animation: spin 1s linear infinite;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Storage Settings -->
        <section id="storage" class="settings-section" style="display: none;">
            <div class="card-table" style="margin-bottom: 0; padding: 2rem;">
                <h3 style="margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                    <i data-lucide="hard-drive" style="color: var(--primary-green);"></i> Media Storage Configuration
                </h3>
                <form class="settings-form" data-section="storage">
                    @csrf
                    <input type="hidden" name="section" value="storage">
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;" class="storage-grid">
                            <div class="form-row">
                                <label>Max Upload Size (MB)</label>
                                <input type="number" name="max_upload_size" value="{{ $settings['max_upload_size'] ?? '100' }}">
                            </div>
                            <div class="form-row">
                                <label>Default Storage Driver</label>
                                <select name="storage_driver">
                                    <option value="local" {{ ($settings['storage_driver'] ?? '') == 'local' ? 'selected' : '' }}>Local (Public)</option>
                                    <option value="s3" {{ ($settings['storage_driver'] ?? '') == 's3' ? 'selected' : '' }}>AWS S3</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <label>Allowed Multimedia Formats</label>
                            <input type="text" name="allowed_formats" value="{{ $settings['allowed_formats'] ?? 'MP4, JPG, PNG, PDF, DOCX' }}" placeholder="E.g., MP4, JPG, PNG">
                            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.5rem;">Comma separated file extensions.</p>
                        </div>
                    </div>
                    <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-primary save-btn" style="padding: 0.8rem 2rem;">
                            <span class="btn-text">{{ __('Save Changes') }}</span>
                            <i data-lucide="loader-2" class="spinner" style="display: none; animation: spin 1s linear infinite;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
    .tab-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        width: 100%;
        border: none;
        background: none;
        border-radius: 14px;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        text-align: left;
    }
    .tab-btn i { width: 18px; }
    .tab-btn:hover { background: #f8fafc; color: var(--text-main); }
    .tab-btn.active { background: #f0fdf4; color: var(--primary-green); }

    /* Toggle Switch */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; }
    input:checked + .slider { background-color: var(--primary-green); }
    input:checked + .slider:before { transform: translateX(20px); }
    .slider.round { border-radius: 34px; }
    .slider.round:before { border-radius: 50%; }

    @media (max-width: 768px) {
        .settings-container {
            grid-template-columns: 1fr !important;
        }
        .storage-grid {
            grid-template-columns: 1fr !important;
        }
        .tab-btn {
            padding: 0.75rem 1rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();

        // Logo upload preview
        const logoInput = document.getElementById('logoInput');
        if (logoInput) {
            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const previewImg = document.getElementById('logoPreviewImg');
                        const initials = document.getElementById('logoInitials');
                        previewImg.src = ev.target.result;
                        previewImg.style.display = 'block';
                        if (initials) initials.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Handle Form Submissions via AJAX
        document.querySelectorAll('.settings-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const btn = this.querySelector('.save-btn');
                const btnText = btn.querySelector('.btn-text');
                const spinner = btn.querySelector('.spinner');
                
                // Set loading state
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none';
                btnText.style.display = 'none';
                spinner.style.display = 'inline-block';

                const formData = new FormData(this);
                
                fetch('{{ route("settings.update") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_csrf"]')?.value || '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    showToast(data.message || 'Settings saved successfully', 'success');
                    if(data.logo_url) {
                        // Logo was updated
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while saving settings', 'error');
                })
                .finally(() => {
                    // Reset loading state
                    btn.style.opacity = '1';
                    btn.style.pointerEvents = 'auto';
                    btnText.style.display = 'inline-block';
                    spinner.style.display = 'none';
                });
            });
        });
    });

    function showSection(id) {
        document.querySelectorAll('.settings-section').forEach(s => s.style.display = 'none');
        document.getElementById(id).style.display = 'block';
        
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }

    function showToast(message, type) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const toastIcon = document.getElementById('toastIcon');
        
        toastMessage.textContent = message;
        
        if (type === 'success') {
            toast.style.background = '#f0fdf4';
            toast.style.border = '1px solid #bbf7d0';
            toast.style.color = '#15803d';
            toastIcon.setAttribute('data-lucide', 'check-circle');
            toastIcon.style.color = '#15803d';
        } else {
            toast.style.background = '#fef2f2';
            toast.style.border = '1px solid #fecaca';
            toast.style.color = '#b91c1c';
            toastIcon.setAttribute('data-lucide', 'x-circle');
            toastIcon.style.color = '#b91c1c';
        }
        
        lucide.createIcons();
        toast.style.display = 'flex';
        
        setTimeout(() => {
            toast.style.display = 'none';
        }, 3000);
    }
</script>
@endsection
