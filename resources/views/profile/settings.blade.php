@extends('layouts.app')
@section('title', 'Account Settings')

@section('content')
<div class="profile-page">
    <div class="profile-page-header">
        <div>
            <h2 style="font-size: 1.5rem; color: var(--text-main);">Account Settings</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">Manage your personal information and preferences</p>
        </div>
        <a href="{{ route('profile.show') }}" class="btn-back-profile">
            <i data-lucide="arrow-left" style="width: 16px;"></i> Back to Profile
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success-modern">
            <i data-lucide="check-circle" style="width: 18px; flex-shrink: 0;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="settings-card">
        <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Avatar Section --}}
            <div class="settings-section">
                <h4 class="settings-section-title">
                    <i data-lucide="camera" style="width: 18px;"></i> Profile Photo
                </h4>
                <div class="avatar-upload-area">
                    <div class="avatar-preview-wrap" id="avatarPreviewWrap">
                        @if($user->profile_photo)
                            <img src="{{ asset('uploads/avatars/' . $user->profile_photo) }}" alt="Avatar" class="avatar-preview-img" id="avatarPreview">
                        @else
                            <div class="avatar-preview-initial" id="avatarInitial">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img src="" alt="Avatar" class="avatar-preview-img" id="avatarPreview" style="display: none;">
                        @endif
                    </div>
                    <div class="avatar-upload-info">
                        <label for="profilePhotoInput" class="btn-upload-avatar">
                            <i data-lucide="upload" style="width: 14px;"></i> Upload New Photo
                        </label>
                        <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" style="display: none;">
                        <p class="avatar-upload-hint">JPG, PNG or WebP. Max 2MB.</p>
                    </div>
                </div>
                @error('profile_photo')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="settings-divider"></div>

            {{-- Personal Info Section --}}
            <div class="settings-section">
                <h4 class="settings-section-title">
                    <i data-lucide="user" style="width: 18px;"></i> Personal Information
                </h4>
                <div class="settings-form-grid">
                    <div class="form-group-modern">
                        <label for="nameInput">Full Name</label>
                        <input type="text" name="name" id="nameInput" value="{{ old('name', $user->name) }}" placeholder="Enter your full name">
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group-modern">
                        <label for="emailInput">Email Address</label>
                        <input type="email" name="email" id="emailInput" value="{{ old('email', $user->email) }}" placeholder="Enter your email">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="settings-divider"></div>

            {{-- Account Details (Read Only) --}}
            <div class="settings-section">
                <h4 class="settings-section-title">
                    <i data-lucide="info" style="width: 18px;"></i> Account Details
                </h4>
                <div class="settings-form-grid">
                    <div class="form-group-modern">
                        <label>Role</label>
                        <div class="readonly-field">
                            <i data-lucide="shield" style="width: 15px; color: var(--primary-green);"></i>
                            {{ $user->role->role_name ?? 'User' }}
                        </div>
                    </div>
                    <div class="form-group-modern">
                        <label>Member Since</label>
                        <div class="readonly-field">
                            <i data-lucide="calendar" style="width: 15px; color: var(--primary-green);"></i>
                            {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="settings-actions">
                <a href="{{ route('profile.show') }}" class="btn-cancel-settings">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="save" style="width: 16px;"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    lucide.createIcons();

    // Avatar preview
    document.getElementById('profilePhotoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('avatarPreview');
                const initial = document.getElementById('avatarInitial');
                preview.src = ev.target.result;
                preview.style.display = 'block';
                if (initial) initial.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
