<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | NexPublish</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @yield('styles')
</head>
<body class="dashboard-body">
    <!-- Sidebar -->
    <div class="sidebar" id="appSidebar">
        <div class="sidebar-header" style="display: flex; justify-content: space-between; align-items: center; padding: 1.5rem 2rem;">
            <h1 id="sidebarLogo" style="margin: 0; font-size: 1.15rem;">NEX<span style="color: var(--primary-green);">PUBLISH</span></h1>
            <button onclick="toggleCollapseSidebar()" class="nav-icon-btn" style="border: none; background: none; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; cursor: pointer; border-radius: 8px; flex-shrink: 0; padding: 0;">
                <i data-lucide="chevron-left" id="collapseIcon" style="width: 18px; height: 18px;"></i>
            </button>
        </div>
        <div class="nav-menu">
            @php
                $userRoleName = strtolower(Auth::user()->role->role_name ?? '');
                $isAdmin = str_contains($userRoleName, 'admin');
                $isVerifier = str_contains($userRoleName, 'verifier');
                $isPublisher = str_contains($userRoleName, 'publisher');
                $isCreator = str_contains($userRoleName, 'creator');
            @endphp

            @if($isCreator)
            <a href="{{ route('creator.dashboard') }}" class="nav-item {{ request()->routeIs('creator.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> <span>{{ __('Creator Dashboard') }}</span>
            </a>
            <a href="{{ route('creator.contents') }}" class="nav-item {{ request()->routeIs('creator.contents') || request()->routeIs('creator.contents.*') ? 'active' : '' }}">
                <i data-lucide="file-text"></i> <span>{{ __('My Content') }}</span>
            </a>
            <a href="{{ route('creator.revisions') }}" class="nav-item {{ request()->routeIs('creator.revisions') ? 'active' : '' }}">
                <i data-lucide="alert-triangle"></i> <span>{{ __('Revision Notes') }}</span>
            </a>
            <a href="{{ route('creator.published') }}" class="nav-item {{ request()->routeIs('creator.published') ? 'active' : '' }}">
                <i data-lucide="globe"></i> <span>{{ __('Published Content') }}</span>
            </a>
            @endif

            @if($isAdmin)
            <a href="{{ route('superadmin.dashboard') }}" class="nav-item {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> <span>{{ __('Super Admin Dashboard') }}</span>
            </a>
            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i data-lucide="users"></i> <span>{{ __('User Management') }}</span>
            </a>
            @endif

            @if($isVerifier)
            <a href="{{ route('verifier.dashboard') }}" class="nav-item {{ request()->routeIs('verifier.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> <span>{{ __('Verifier Dashboard') }}</span>
            </a>
            @endif

            @if($isPublisher)
            <a href="{{ route('publisher.dashboard') }}" class="nav-item {{ request()->routeIs('publisher.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> <span>{{ __('Publisher Dashboard') }}</span>
            </a>
            @endif

            @if($isAdmin) {{-- Only Super Admin sees global Content Management --}}
            <a href="{{ route('contents.index') }}" class="nav-item {{ request()->routeIs('contents.*') ? 'active' : '' }}">
                <i data-lucide="file-text"></i> <span>{{ __('Content Management') }}</span>
            </a>
            @endif

            @if(($isAdmin || $isVerifier) && !$isPublisher && !$isCreator)
            <div class="sidebar-section-title">{{ __('Verification') }}</div>
            <a href="{{ route('reviews.index') }}" class="nav-item {{ request()->routeIs('reviews.index') ? 'active' : '' }}">
                <i data-lucide="clipboard-check"></i> <span>{{ __('Review Queue') }}</span>
            </a>
            @if($isVerifier)
            <a href="{{ route('reviews.approved') }}" class="nav-item {{ request()->routeIs('reviews.approved') ? 'active' : '' }}">
                <i data-lucide="check-circle"></i> <span>{{ __('Approved Content') }}</span>
            </a>
            <a href="{{ route('reviews.rejected') }}" class="nav-item {{ request()->routeIs('reviews.rejected') ? 'active' : '' }}">
                <i data-lucide="x-circle"></i> <span>{{ __('Rejected Content') }}</span>
            </a>
            @endif
            @endif

            @if(($isAdmin || $isPublisher) && !$isCreator)
            <div class="sidebar-section-title">{{ __('Publishing') }}</div>
            @if($isAdmin)
            <a href="{{ route('publish.index') }}" class="nav-item {{ request()->routeIs('publish.index') ? 'active' : '' }}">
                <i data-lucide="send"></i> <span>{{ __('Publish Queue') }}</span>
            </a>
            @endif
            @if($isPublisher)
            <a href="{{ route('publisher.queue') }}" class="nav-item {{ request()->routeIs('publisher.queue') ? 'active' : '' }}">
                <i data-lucide="send"></i> <span>{{ __('Publish Queue') }}</span>
            </a>
            <a href="{{ route('publisher.scheduled') }}" class="nav-item {{ request()->routeIs('publisher.scheduled') ? 'active' : '' }}">
                <i data-lucide="calendar"></i> <span>{{ __('Scheduled Content') }}</span>
            </a>
            @endif
            @endif
            
            @if(!$isCreator)
            <a href="{{ $isPublisher ? route('publisher.published') : route('archive.index') }}" class="nav-item {{ (request()->routeIs('archive.index') || request()->routeIs('publisher.published')) ? 'active' : '' }}">
                <i data-lucide="archive"></i> <span>{{ ($isVerifier || $isPublisher) ? __('History Logs') : __('Published Content') }}</span>
            </a>
            @endif

            @if($isPublisher)
            <a href="{{ route('publisher.logs') }}" class="nav-item {{ request()->routeIs('publisher.logs') ? 'active' : '' }}">
                <i data-lucide="history"></i> <span>{{ __('Activity Logs') }}</span>
            </a>
            @endif

            <div class="sidebar-section-title">{{ __('Account') }}</div>
            <a href="{{ route('profile.show') }}" class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i data-lucide="user"></i> <span>{{ __('My Profile') }}</span>
            </a>

            @if($isAdmin)
            <div class="sidebar-section-title">{{ __('System') }}</div>
            <a href="{{ route('settings.index') }}" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i data-lucide="settings-2"></i> <span>{{ __('System Settings') }}</span>
            </a>
            @endif
        </div>

    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Navbar -->
        <div class="navbar">
            <div class="mobile-toggle" onclick="toggleSidebar()">
                <i data-lucide="menu"></i>
            </div>
            <div class="breadcrumb">
                <a href="{{ $isVerifier ? route('verifier.dashboard') : ($isPublisher ? route('publisher.dashboard') : ($isCreator ? route('creator.dashboard') : ($isAdmin ? route('superadmin.dashboard') : '#'))) }}">{{ __('Dashboard') }}</a>
                @if(!request()->routeIs('superadmin.dashboard') && !request()->routeIs('verifier.dashboard'))
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current">
                        @if(request()->routeIs('users.*')) {{ __('User Management') }}
                        @elseif(request()->routeIs('contents.*')) {{ __('Content Management') }}
                        @elseif(request()->routeIs('reviews.*')) {{ __('Review Queue') }}
                        @elseif(request()->routeIs('publish.*') || request()->routeIs('publisher.queue')) {{ __('Publish Queue') }}
                        @elseif(request()->routeIs('publisher.scheduled')) {{ __('Scheduled Content') }}
                        @elseif(request()->routeIs('archive.*') || request()->routeIs('publisher.published')) {{ __('Published Content') }}
                        @elseif(request()->routeIs('publisher.logs')) {{ __('Activity Logs') }}
                        @elseif(request()->routeIs('profile.show')) {{ __('My Profile') }}
                        @elseif(request()->routeIs('profile.settings')) {{ __('Account Settings') }}
                        @elseif(request()->routeIs('profile.password')) {{ __('Change Password') }}
                        @else {{ ucfirst(request()->path()) }}
                        @endif
                    </span>
                @endif
            </div>

            <div class="nav-right">
                <!-- Notifications -->
                <div class="dropdown-container">
                    <div class="nav-icon-btn" onclick="toggleDropdown('notificationDropdown')">
                        <i data-lucide="bell" style="width: 20px;"></i>
                        <span class="unread-dot"></span>
                    </div>
                    <div id="notificationDropdown" class="dropdown-menu">
                        <div class="dropdown-header">
                            <h4>{{ __('Notifications') }}</h4>
                            <span style="font-size: 0.7rem; color: var(--primary-green); cursor: pointer; font-weight: 700;">{{ __('Mark all as read') }}</span>
                        </div>
                        <div class="dropdown-list">
                            <div class="notification-item">
                                <div class="notif-icon" style="background: #f0fdf4; color: #10b981;">
                                    <i data-lucide="check-circle" style="width: 18px;"></i>
                                </div>
                                <div class="notif-content">
                                    <p class="notif-title">{{ __('Content Approved') }}</p>
                                    <p class="notif-desc">{{ __('Your content "Summer Campaign" has been approved.') }}</p>
                                    <p class="notif-time">{{ __('2 minutes ago') }}</p>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notif-icon" style="background: #fef2f2; color: #ef4444;">
                                    <i data-lucide="x-circle" style="width: 18px;"></i>
                                </div>
                                <div class="notif-content">
                                    <p class="notif-title">{{ __('Content Rejected') }}</p>
                                    <p class="notif-desc">{{ __('"Product Launch Video" needs revision.') }}</p>
                                    <p class="notif-time">{{ __('1 hour ago') }}</p>
                                </div>
                            </div>
                            <div class="notification-item">
                                <div class="notif-icon" style="background: #fff7ed; color: #f97316;">
                                    <i data-lucide="clock" style="width: 18px;"></i>
                                </div>
                                <div class="notif-content">
                                    <p class="notif-title">Waiting Review</p>
                                    <p class="notif-desc">5 new contents are waiting for your verification.</p>
                                    <p class="notif-time">3 hours ago</p>
                                </div>
                            </div>
                        </div>
                        <div style="padding: 1rem; text-align: center; border-top: 1px solid var(--border-color);">
                            <a href="#" style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); text-decoration: none;">View all notifications</a>
                        </div>
                    </div>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown-container">
                    <div class="profile-btn" onclick="toggleDropdown('profileDropdown')">
                        <div class="profile-avatar-wrap">
                            @if(Auth::user()->profile_photo)
                                <img src="{{ asset('uploads/avatars/' . Auth::user()->profile_photo) }}" alt="Avatar" class="profile-avatar-img">
                            @else
                                <div class="profile-avatar">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="profile-online-dot"></span>
                        </div>
                        <div class="profile-info">
                            <span class="name">{{ Auth::user()->name }}</span>
                            <span class="role">{{ Auth::user()->role->role_name ?? 'User' }}</span>
                        </div>
                        <i data-lucide="chevron-down" style="width: 14px; color: var(--text-muted); transition: transform 0.2s;"></i>
                    </div>
                    <div id="profileDropdown" class="dropdown-menu profile-dropdown-modern">
                        {{-- Dropdown Header --}}
                        <div class="profile-dd-header">
                            <div class="profile-dd-avatar-wrap">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('uploads/avatars/' . Auth::user()->profile_photo) }}" alt="Avatar" class="profile-dd-avatar-img">
                                @else
                                    <div class="profile-dd-avatar-initial">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="profile-dd-info">
                                <p class="profile-dd-name">{{ Auth::user()->name }}</p>
                                <p class="profile-dd-email">{{ Auth::user()->email }}</p>
                                <span class="profile-dd-role-badge">{{ Auth::user()->role->role_name ?? 'User' }}</span>
                            </div>
                        </div>

                        {{-- Menu Items --}}
                        <div class="profile-dd-menu">
                            <a href="{{ route('profile.show') }}" class="profile-dd-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                                <div class="profile-dd-item-icon">
                                    <i data-lucide="user" style="width: 16px;"></i>
                                </div>
                                <div class="profile-dd-item-text">
                                    <span class="profile-dd-item-title">My Profile</span>
                                    <span class="profile-dd-item-desc">View account information</span>
                                </div>
                            </a>
                            <a href="{{ route('profile.settings') }}" class="profile-dd-item {{ request()->routeIs('profile.settings') ? 'active' : '' }}">
                                <div class="profile-dd-item-icon">
                                    <i data-lucide="sliders" style="width: 16px;"></i>
                                </div>
                                <div class="profile-dd-item-text">
                                    <span class="profile-dd-item-title">Account Settings</span>
                                    <span class="profile-dd-item-desc">Edit profile & preferences</span>
                                </div>
                            </a>
                            <a href="{{ route('profile.password') }}" class="profile-dd-item {{ request()->routeIs('profile.password') ? 'active' : '' }}">
                                <div class="profile-dd-item-icon">
                                    <i data-lucide="lock" style="width: 16px;"></i>
                                </div>
                                <div class="profile-dd-item-text">
                                    <span class="profile-dd-item-title">Change Password</span>
                                    <span class="profile-dd-item-desc">Update your password</span>
                                </div>
                            </a>
                        </div>

                        {{-- Logout --}}
                        <div class="profile-dd-footer">
                            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                @csrf
                                <a href="#" class="profile-dd-logout" onclick="event.preventDefault(); document.getElementById('logoutForm').submit()">
                                    <i data-lucide="log-out" style="width: 16px;"></i>
                                    <span>Logout</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <script>
        lucide.createIcons();

        function toggleCollapseSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const collapseIcon = document.getElementById('collapseIcon');
            
            sidebar.classList.toggle('sidebar-collapsed');
            
            if (sidebar.classList.contains('sidebar-collapsed')) {
                collapseIcon.setAttribute('data-lucide', 'chevron-right');
            } else {
                collapseIcon.setAttribute('data-lucide', 'chevron-left');
            }
            lucide.createIcons();
        }

        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const isVisible = dropdown.style.display === 'block';
            
            // Close all dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(d => d.style.display = 'none');
            
            if (!isVisible) {
                dropdown.style.display = 'block';
            }
        }

        // Close on click outside
        window.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(d => d.style.display = 'none');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
