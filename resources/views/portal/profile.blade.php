@extends('layouts.portal')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('portal.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0">
                    <i class="bi bi-person me-2"></i>Edit Profile
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('portal.profile.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   id="first_name"
                                   name="first_name"
                                   value="{{ old('first_name', $user->first_name) }}"
                                   required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   id="last_name"
                                   name="last_name"
                                   value="{{ old('last_name', $user->last_name) }}"
                                   required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $user->email) }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="grade" class="form-label">Current Grade</label>
                        <select class="form-select @error('grade') is-invalid @enderror"
                                id="grade"
                                name="grade"
                                required>
                            <option value="grade_9_10" {{ old('grade', $user->grade) === 'grade_9_10' ? 'selected' : '' }}>
                                Grade 9 & 10
                            </option>
                            <option value="grade_11" {{ old('grade', $user->grade) === 'grade_11' ? 'selected' : '' }}>
                                Grade 11
                            </option>
                            <option value="grade_12" {{ old('grade', $user->grade) === 'grade_12' ? 'selected' : '' }}>
                                Grade 12
                            </option>
                        </select>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>
                        <a href="{{ route('portal.dashboard') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Account Info</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <strong>Full Name:</strong><br>
                        {{ $user->full_name }}
                    </li>
                    <li class="mb-2">
                        <strong>Email:</strong><br>
                        {{ $user->email }}
                    </li>
                    <li class="mb-2">
                        <strong>Grade Level:</strong><br>
                        <span class="badge bg-primary">{{ $user->grade_display }}</span>
                    </li>
                    <li>
                        <strong>Member Since:</strong><br>
                        {{ $user->created_at->format('F j, Y') }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
