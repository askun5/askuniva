@extends('layouts.portal')

@section('title', 'Profile')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <h1 class="mt-0 mb-4">Profile</h1>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>Edit Profile
                </h5>
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

                    <div class="mb-3">
                        <label for="zip_code" class="form-label">Zip Code</label>
                        <div class="position-relative">
                            <input type="text"
                                   class="form-control @error('zip_code') is-invalid @enderror"
                                   id="zip_code"
                                   name="zip_code"
                                   value="{{ old('zip_code', $user->zip_code) }}"
                                   maxlength="10"
                                   autocomplete="off">
                            @error('zip_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="zip-suggestions" class="list-group position-absolute w-100" style="display:none; z-index:1050;"></div>
                        </div>
                    </div>

                    <div class="row mb-3" id="city-state-row" style="{{ old('city', $user->city) ? '' : 'display:none;' }}">
                        <div class="col-md-8">
                            <label for="city" class="form-label">City</label>
                            <input type="text"
                                   class="form-control"
                                   id="city"
                                   name="city"
                                   value="{{ old('city', $user->city) }}"
                                   readonly>
                        </div>
                        <div class="col-md-4">
                            <label for="state" class="form-label">State</label>
                            <input type="text"
                                   class="form-control"
                                   id="state"
                                   name="state"
                                   value="{{ old('state', $user->state) }}"
                                   readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="grade" class="form-label">Academic Level</label>
                        <select class="form-select @error('grade') is-invalid @enderror"
                                id="grade"
                                name="grade"
                                required>
                            <option value="grade_9_10" {{ old('grade', $user->grade) === 'grade_9_10' ? 'selected' : '' }}>
                                High School (Grades 9 & 10)
                            </option>
                            <option value="grade_11" {{ old('grade', $user->grade) === 'grade_11' ? 'selected' : '' }}>
                                High School (Grade 11)
                            </option>
                            <option value="grade_12" {{ old('grade', $user->grade) === 'grade_12' ? 'selected' : '' }}>
                                High School (Grade 12)
                            </option>
                            <option value="community_college" {{ old('grade', $user->grade) === 'community_college' ? 'selected' : '' }}>
                                Community College
                            </option>
                            <option value="undergraduate" {{ old('grade', $user->grade) === 'undergraduate' ? 'selected' : '' }}>
                                Undergraduate (University)
                            </option>
                            <option value="graduate" {{ old('grade', $user->grade) === 'graduate' ? 'selected' : '' }}>
                                Graduate (Master's/PhD)
                            </option>
                            <option value="gap_year" {{ old('grade', $user->grade) === 'gap_year' ? 'selected' : '' }}>
                                Gap Year
                            </option>
                        </select>
                        @error('grade')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex flex-column flex-md-row gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>
                        <a href="{{ route('portal.dashboard') }}" class="btn btn-outline-primary btn-lg">
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
                        <strong>Academic Level:</strong><br>
                        <span class="badge bg-primary">{{ $user->grade_display }}</span>
                    </li>
                    @if($user->zip_code)
                    <li class="mb-2">
                        <strong>Location:</strong><br>
                        {{ $user->city ? $user->city . ', ' . $user->state . ' ' : '' }}{{ $user->zip_code }}
                    </li>
                    @endif
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

@push('scripts')
<script>
    const zipInput       = document.getElementById('zip_code');
    const zipSuggestions = document.getElementById('zip-suggestions');
    const cityInput      = document.getElementById('city');
    const stateInput     = document.getElementById('state');
    const cityStateRow   = document.getElementById('city-state-row');
    let zipTimer;

    zipInput.addEventListener('input', function () {
        const zip = this.value.replace(/\D/g, '').slice(0, 5);
        this.value = zip;

        clearTimeout(zipTimer);
        zipSuggestions.style.display = 'none';
        zipSuggestions.innerHTML = '';

        if (zip.length === 5) {
            zipTimer = setTimeout(() => lookupZip(zip), 350);
        } else {
            cityInput.value  = '';
            stateInput.value = '';
            cityStateRow.style.display = 'none';
        }
    });

    async function lookupZip(zip) {
        try {
            const res = await fetch(`https://api.zippopotam.us/us/${zip}`);
            if (!res.ok) {
                showZipMessage('No location found for this zip code.');
                return;
            }
            const data = await res.json();
            zipSuggestions.innerHTML = '';

            data.places.forEach(place => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'list-group-item list-group-item-action';
                btn.innerHTML = `<i class="bi bi-geo-alt me-2 text-muted"></i>${place['place name']}, ${place['state abbreviation']} <span class="text-muted small ms-1">${zip}</span>`;
                btn.addEventListener('click', () => {
                    cityInput.value  = place['place name'];
                    stateInput.value = place['state abbreviation'];
                    cityStateRow.style.display = '';
                    zipSuggestions.style.display = 'none';
                });
                zipSuggestions.appendChild(btn);
            });

            zipSuggestions.style.display = 'block';
        } catch (e) {
            showZipMessage('Could not look up zip code. Please try again.');
        }
    }

    function showZipMessage(msg) {
        zipSuggestions.innerHTML = `<div class="list-group-item text-muted small py-2">${msg}</div>`;
        zipSuggestions.style.display = 'block';
    }

    document.addEventListener('click', function (e) {
        if (!zipInput.contains(e.target) && !zipSuggestions.contains(e.target)) {
            zipSuggestions.style.display = 'none';
        }
    });
</script>
@endpush
