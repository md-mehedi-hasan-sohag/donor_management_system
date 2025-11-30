@extends('layouts.app')

@section('title', 'Edit Campaign - DonorLink')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Edit Campaign</h1>
        <p style="opacity: 0.9;">Update your campaign details</p>
    </div>

    <div class="form-body">
        <form action="{{ route('campaigns.update', $campaign) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Campaign Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $campaign->title) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $campaign->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="10" required>{{ old('description', $campaign->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2">
                <div class="form-group">
                    <label class="form-label">Funding Goal (USD) *</label>
                    <input type="number" name="goal_amount" class="form-control" value="{{ old('goal_amount', $campaign->goal_amount) }}" min="100" step="0.01" required>
                </div>

                <div class="form-group">
                    <label class="form-label">End Date *</label>
                    <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Location *</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', $campaign->location) }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Campaign Image</label>
                @if($campaign->image_path)
                    <div style="margin-bottom: 1rem;">
                        <img src="{{ asset('storage/' . $campaign->image_path) }}" alt="Current image" style="max-width: 300px; border-radius: 0.5rem;">
                        <p style="color: var(--gray-600); font-size: 0.875rem; margin-top: 0.5rem;">Current image (leave empty to keep this image)</p>
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                <div class="image-preview" id="imagePreview" style="display: none;"></div>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="is_urgent" value="1" {{ old('is_urgent', $campaign->is_urgent) ? 'checked' : '' }}>
                    <span>🔥 Mark as Urgent</span>
                </label>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="accepts_volunteers" value="1" {{ old('accepts_volunteers', $campaign->accepts_volunteers) ? 'checked' : '' }}>
                    <span>👋 Accept Volunteers</span>
                </label>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 0.5rem;">
                    <input type="checkbox" name="accepts_in_kind" value="1" id="acceptsInKind" {{ old('accepts_in_kind', $campaign->accepts_in_kind) ? 'checked' : '' }} onchange="toggleInKindNeeds()">
                    <span>📦 Accept In-Kind Donations</span>
                </label>
            </div>

            <div class="form-group" id="inKindNeedsGroup" style="display: {{ old('accepts_in_kind', $campaign->accepts_in_kind) ? 'block' : 'none' }};">
                <label class="form-label">What items do you need?</label>
                <textarea name="in_kind_needs" class="form-control" rows="4">{{ old('in_kind_needs', $campaign->in_kind_needs) }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">💾 Update Campaign</button>
                <a href="{{ route('campaigns.show', $campaign) }}" class="btn btn-outline">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
        preview.style.display = 'flex';
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        }
        reader.readAsDataURL(file);
    }
}

function toggleInKindNeeds() {
    const checkbox = document.getElementById('acceptsInKind');
    const group = document.getElementById('inKindNeedsGroup');
    group.style.display = checkbox.checked ? 'block' : 'none';
}
</script>
@endsection
