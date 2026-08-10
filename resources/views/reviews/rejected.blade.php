@extends('layouts.app')

@section('title', 'Rejected Content')

@section('content')
<div class="header-section">
    <h2>Rejected Content</h2>
    <p>Contents that did not pass verification and require revision.</p>
</div>

<div class="card-table">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Creator</th>
                <th>Rejection Note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contents as $content)
            <tr>
                <td><strong>{{ $content->title }}</strong></td>
                <td>{{ $content->content_type }}</td>
                <td>{{ $content->creator->name ?? 'System' }}</td>
                <td>
                    <span style="font-size: 0.8rem; color: #ef4444; background: #fef2f2; padding: 0.25rem 0.5rem; border-radius: 4px; display: inline-block; max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        {{ $content->rejection_note }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('contents.show', $content->id) }}" class="btn-icon">
                        <i data-lucide="eye" style="width: 16px;"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 3rem;">No rejected content yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 1rem;">
        {{ $contents->links() }}
    </div>
</div>
@endsection
