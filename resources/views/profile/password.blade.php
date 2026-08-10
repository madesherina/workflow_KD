@extends('layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="profile-page">
    <div class="profile-page-header">
        <div>
            <h2 style="font-size: 1.5rem; color: var(--text-main);">Change Password</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">Update your password to keep your account secure</p>
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

    <div class="settings-card" style="max-width: 600px;">
        <form action="{{ route('profile.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="settings-section">
                <div class="password-security-banner">
                    <div class="security-icon-wrap">
                        <i data-lucide="shield-check" style="width: 24px;"></i>
                    </div>
                    <div>
                        <h4>Password Security</h4>
                        <p>Choose a strong password with at least 8 characters including uppercase, lowercase, numbers and symbols.</p>
                    </div>
                </div>
            </div>

            <div class="settings-divider"></div>

            <div class="settings-section">
                <div class="form-group-modern" style="margin-bottom: 1.25rem;">
                    <label for="currentPwd">Current Password</label>
                    <div class="password-input-wrap">
                        <i data-lucide="lock" class="input-icon" style="width: 16px;"></i>
                        <input type="password" name="current_password" id="currentPwd" placeholder="Enter your current password" style="padding-left: 2.75rem;">
                    </div>
                    @error('current_password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group-modern" style="margin-bottom: 1.25rem;">
                    <label for="newPwd">New Password</label>
                    <div class="password-input-wrap">
                        <i data-lucide="key" class="input-icon" style="width: 16px;"></i>
                        <input type="password" name="new_password" id="newPwd" placeholder="Enter your new password" style="padding-left: 2.75rem;">
                    </div>
                    @error('new_password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label for="confirmPwd">Confirm New Password</label>
                    <div class="password-input-wrap">
                        <i data-lucide="key" class="input-icon" style="width: 16px;"></i>
                        <input type="password" name="new_password_confirmation" id="confirmPwd" placeholder="Confirm your new password" style="padding-left: 2.75rem;">
                    </div>
                </div>
            </div>

            <div class="settings-actions">
                <a href="{{ route('profile.show') }}" class="btn-cancel-settings">Cancel</a>
                <button type="submit" class="btn-primary">
                    <i data-lucide="check" style="width: 16px;"></i> Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>lucide.createIcons();</script>
@endsection
