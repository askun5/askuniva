@extends('layouts.admin')

@section('title', 'Contact Submissions')

@section('content')
<h1 class="mb-4">Contact Submissions</h1>

<div class="card">
    <div class="card-body">
        @if($submissions->count() > 0)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Email</th>
                        <th>Message Preview</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                        <tr class="{{ !$submission->is_read ? 'table-warning' : '' }}">
                            <td>
                                @if(!$submission->is_read)
                                    <span class="badge bg-warning text-dark">New</span>
                                @else
                                    <span class="badge bg-secondary">Read</span>
                                @endif
                            </td>
                            <td>{{ $submission->email }}</td>
                            <td>{{ Str::limit($submission->comments, 50) }}</td>
                            <td>{{ $submission->created_at->format('M j, Y g:i A') }}</td>
                            <td>
                                <a href="{{ route('admin.contacts.show', $submission) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $submission) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this submission?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox display-1"></i>
                <p class="mt-3">No contact submissions yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection
