@extends('layouts.admin')
@section('title', 'Deleted Researches — Trash')
@section('page-title', 'Trash')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <p style="color:var(--muted); font-size:14px;">These researches were deleted by admins. You can restore or permanently delete them.</p>
    @if($deletedResearches->count() > 0)
    <form method="POST" action="{{ route('super-admin.trash.empty') }}"
        onsubmit="return confirm('Permanently delete ALL researches in trash? This cannot be undone!')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-red btn-sm">Empty Trash</button>
    </form>
    @endif
</div>

<div class="admin-card">
    <div class="admin-card-header">
        <h3>Deleted Researches</h3>
        <span style="font-size:13px; color:var(--muted);">{{ $deletedResearches->total() }} item(s)</span>
    </div>

    @if($deletedResearches->isEmpty())
        <div style="text-align:center; padding:48px; color:var(--muted);">
            <p style="font-size:16px;">Trash is empty.</p>
        </div>
    @else
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th>Year</th>
                    <th>Deleted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedResearches as $r)
                <tr>
                    <td>
                        <strong style="color:var(--purple-deep);">{{ Str::limit($r->title, 45) }}</strong>
                    </td>
                    <td>{{ $r->author_name }}</td>
                    <td style="font-size:12px; color:var(--muted);">{{ Str::limit($r->department, 25) }}</td>
                    <td><span class="type-badge type-{{ $r->type }} badge-sm">{{ $r->getTypeLabel() }}</span></td>
                    <td>{{ $r->year_published }}</td>
                    <td style="font-size:12px; color:var(--muted);">{{ $r->deleted_at->diffForHumans() }}</td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <form method="POST" action="{{ route('super-admin.trash.restore', $r->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-green">Restore</button>
                            </form>
                            <form method="POST" action="{{ route('super-admin.trash.destroy', $r->id) }}"
                                onsubmit="return confirm('Permanently delete this research? This cannot be undone!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-red">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $deletedResearches->links() }}</div>
    @endif
</div>

@endsection