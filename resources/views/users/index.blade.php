@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="header-section">
    <h2>{{ __('User Management') }}</h2>
    <p>{{ __('Manage administrator accounts, roles, and system access.') }}</p>
</div>

<div class="card-table">
    <div class="card-table-header">
        <h3>{{ __('System Users') }}</h3>
        <button class="btn-primary" onclick="openModal('addModal')">
            <i data-lucide="plus" style="width: 16px;"></i> {{ __('Add New User') }}
        </button>
    </div>

    @if(session('success'))
        <div style="background: #f0fdf4; color: #15803d; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; color: #b91c1c; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.9rem; font-weight: 600;">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; color: #b91c1c; padding: 1rem; border-radius: 12px; margin-bottom: 1rem; font-size: 0.85rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Role') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; overflow: hidden; background: #f1f5f9; flex-shrink: 0;">
                            @if($user->profile_photo)
                                <img src="{{ asset('uploads/avatars/' . $user->profile_photo) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: var(--primary-green); color: white; font-size: 0.75rem; font-weight: 800;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div style="font-weight: 700;">{{ $user->name }}</div>
                    </div>
                </td>
                <td style="color: var(--text-muted);">{{ $user->email }}</td>
                <td>
                    <span class="badge {{ $user->role && $user->role->role_name == 'Super Admin' ? 'badge-orange' : 'badge-blue' }}">
                        {{ $user->role->role_name ?? __('No Role') }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-green">{{ __('Active') }}</span>
                </td>
                <td>
                    <div class="action-btns">
                        <button class="btn-icon" onclick="openEditModal({{ $user }})">
                            <i data-lucide="edit-2" style="width: 16px;"></i>
                        </button>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete">
                                <i data-lucide="trash-2" style="width: 16px;"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('Add New Administrator') }}</h3>
        </div>
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="form-row">
                <label>{{ __('Full Name') }}</label>
                <input type="text" name="name" placeholder="{{ __('Enter full name') }}" required>
            </div>
            <div class="form-row">
                <label>{{ __('Email Address') }}</label>
                <input type="email" name="email" placeholder="email@example.com" required>
            </div>
            <div class="form-row">
                <label>{{ __('Password') }}</label>
                <input type="password" name="password" placeholder="{{ __('Min 8 characters') }}" required>
            </div>
            <div class="form-row">
                <label>{{ __('Assign Role') }}</label>
                <select name="role_id" required>
                    <option value="" disabled selected>{{ __('Select a role') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn-primary" style="background: #f1f5f9; color: #64748b;" onclick="closeModal('addModal')">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Create Account') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>{{ __('Edit Administrator') }}</h3>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-row">
                <label>{{ __('Full Name') }}</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="form-row">
                <label>{{ __('Email Address') }}</label>
                <input type="email" name="email" id="edit_email" required>
            </div>
            <div class="form-row">
                <label>{{ __('Password (Leave blank to keep current)') }}</label>
                <input type="password" name="password" placeholder="••••••••">
            </div>
            <div class="form-row">
                <label>{{ __('Assign Role') }}</label>
                <select name="role_id" id="edit_role_id" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="button" class="btn-primary" style="background: #f1f5f9; color: #64748b;" onclick="closeModal('editModal')">{{ __('Cancel') }}</button>
                <button type="submit" class="btn-primary">{{ __('Save Changes') }}</button>
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

    function openEditModal(user) {
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role_id').value = user.role_id;
        document.getElementById('editForm').action = '/users/' + user.id;
        openModal('editModal');
    }

    // Close on click outside
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            event.target.style.display = 'none';
        }
    }
</script>
@endsection
