@extends('layouts.app')

@section('title', 'Approved Content')

@section('content')
<div class="header-section">
    <h2>Approved Content</h2>
    <p>List of all contents that have been verified and approved.</p>
</div>

<div class="card-table">
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Creator</th>
                <th>Approved At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contents as $content)
            <tr>
                <td><strong>{{ $content->title }}</strong></td>
                <td>{{ $content->content_type }}</td>
                <td>{{ $content->creator->name ?? 'System' }}</td>
                <td>{{ $content->updated_at->format('d M Y H:i') }}</td>
                <td>
                    <a href="{{ route('contents.show', $content->id) }}" class="btn-icon">
                        <i data-lucide="eye" style="width: 16px;"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 3rem;">No approved content yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding: 1rem;">
        {{ $contents->links() }}
    </div>
</div>
@endsection
