@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="profile-page">
    <div class="profile-page-header">
        <h2 style="font-size: 1.5rem; color: var(--text-main);">My Profile</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 0;">View your account information</p>
    </div>

    <div class="profile-card-container">
        {{-- Profile Card --}}
        <div class="profile-detail-card">
            <div class="profile-cover"></div>
            <div class="profile-card-body">
                <div class="profile-card-avatar-wrap">
                    @if($user->profile_photo)
                        <img src="{{ asset('uploads/avatars/' . $user->profile_photo) }}" alt="Avatar" class="profile-card-avatar-img">
                    @else
                        <div class="profile-card-avatar-initial">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="profile-card-status-dot"></div>
                </div>

                <h3 class="profile-card-name">{{ $user->name }}</h3>
                <span class="profile-card-role-badge">
                    <i data-lucide="shield" style="width: 13px; height: 13px;"></i>
                    {{ $user->role->role_name ?? 'User' }}
                </span>
            </div>
        </div>

        {{-- Info Card --}}
        <div class="profile-info-card">
            <div class="profile-info-header">
                <h3>Account Information</h3>
                <a href="{{ route('profile.settings') }}" class="btn-edit-profile">
                    <i data-lucide="edit-3" style="width: 14px;"></i> Edit Profile
                </a>
            </div>

            <div class="profile-info-grid">
                <div class="profile-info-item">
                    <div class="profile-info-icon">
                        <i data-lucide="user" style="width: 18px;"></i>
                    </div>
                    <div>
                        <span class="profile-info-label">Full Name</span>
                        <span class="profile-info-value">{{ $user->name }}</span>
                    </div>
                </div>
                <div class="profile-info-item">
                    <div class="profile-info-icon">
                        <i data-lucide="mail" style="width: 18px;"></i>
                    </div>
                    <div>
                        <span class="profile-info-label">Email Address</span>
                        <span class="profile-info-value">{{ $user->email }}</span>
                    </div>
                </div>
                <div class="profile-info-item">
                    <div class="profile-info-icon">
                        <i data-lucide="shield-check" style="width: 18px;"></i>
                    </div>
                    <div>
                        <span class="profile-info-label">Role</span>
                        <span class="profile-info-value">{{ $user->role->role_name ?? 'User' }}</span>
                    </div>
                </div>
                <div class="profile-info-item">
                    <div class="profile-info-icon">
                        <i data-lucide="calendar" style="width: 18px;"></i>
                    </div>
                    <div>
                        <span class="profile-info-label">Member Since</span>
                        <span class="profile-info-value">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="profile-quick-actions">
                <a href="{{ route('profile.settings') }}" class="quick-action-btn">
                    <i data-lucide="sliders" style="width: 16px;"></i>
                    Account Settings
                </a>
                <a href="{{ route('profile.password') }}" class="quick-action-btn">
                    <i data-lucide="lock" style="width: 16px;"></i>
                    Change Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>lucide.createIcons();</script>
@endsection
