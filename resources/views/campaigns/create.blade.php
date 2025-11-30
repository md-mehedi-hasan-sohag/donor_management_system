@extends('layouts.app')

@section('title', 'Create Campaign - DonorLink')

@section('content')
<style>
    .form-container {
        max-width: 900px;
        margin: 2rem auto;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .form-body {
        padding: 2rem;
    }

    .form-step {
        display: none;
    }

    .form-step.active {
        display: block;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--gray-200);
        z-index: 0;
    }

    .step {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--gray-200);
        color: var(--gray-600);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        position: relative;
        z-index: 1;
        transition: all 0.3s;
    }

    .step.active {
        background: var(--primary);
        color: white;
        transform: scale(1.1);
    }

    .step.completed {
        background: var(--success);
        color: white;
    }

    .image-preview {
        width: 100%;
        height: 200px;
        border: 2px dashed var(--gray-300);
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 0.5rem;
        overflow: hidden;
        background: var(--gray-50);
    }

    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: cover;
    }

    .checkbox-card {
        padding: 1rem;
        border: 2px solid var(--gray-300);
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .checkbox-card:hover {
        border-color: var(--primary);
        background: var(--gray-50);
    }

    .checkbox-card input:checked + .checkbox-content {
        border-color: var(--primary);
        background: #ede9fe;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h1 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">Create a New Campaign</h1>
        <p style="opacity: 0.9;">Share your cause with the world and start making an impact</p>
    </div>

    <div class="form-body">
        <form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data" id="campaignForm">
            @csrf

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" data-step="1">1</div>
                <div class="step" data-step="2">2</div>
                <div class="step" data-step="3">3</div>
                <div class="step" data-step="4">4</div>
            </div>

            <!-- Step 1: Basic Information -->
            <div class="form-step active" data-step="1">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--gray-900);">📝 Basic Information</h2>

                <div class="form-group">
                    <label class="form-label">Campaign Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Give your campaign a clear, compelling title">
                    <small style="color: var(--gray-500);">Make it specific and inspiring (e.g., "Help 100 Children Get School Supplies")</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Category *</label>
                    <select name="category_id" class="form-control" required>
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Location *</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location') }}" required placeholder="City, State or Region">
                </div>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Funding Goal (USD) *</label>
                        <input type="number" name="goal_amount" class="form-control" value="{{ old('goal_amount') }}" min="100" step="0.01" required placeholder="5000">
                    </div>

                    <div class="form-group">
                        <label class="form-label">End Date *</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                    </div>
                </div>
            </div>

            <!-- Step 2: Campaign Story -->
            <div class="form-step" data-step="2">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--gray-900);">📖 Your Story</h2>

                <div class="form-group">
                    <label class="form-label">Campaign Description *</label>
                    <textarea name="description" class="form-control" rows="12" required placeholder="Tell your story...

What is the problem you're trying to solve?
Why is this important?
How will the funds be used?
What impact will this create?">{{ old('description') }}</textarea>
                    <small style="color: var(--gray-500);">Be detailed and authentic. Share your passion and vision.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Campaign Image *</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)" required>
                    <small style="color: var(--gray-500);">Upload a high-quality image that represents your campaign (Max 2MB)</small>
                    <div class="image-preview" id="imagePreview">
                        <span style="color: var(--gray-400);">Image preview will appear here</span>
                    </div>
                </div>
            </div>

            <!-- Step 3: Additional Options -->
            <div class="form-step" data-step="3">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--gray-900);">⚙️ Campaign Options</h2>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="is_urgent" value="1" {{ old('is_urgent') ? 'checked' : '' }}>
                        <span class="form-label" style="margin-bottom: 0;">🔥 Mark as Urgent</span>
                    </label>
                    <small style="color: var(--gray-500); display: block; margin-top: 0.25rem; margin-left: 1.75rem;">
                        Urgent campaigns get highlighted visibility on the platform
                    </small>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="accepts_volunteers" value="1" {{ old('accepts_volunteers') ? 'checked' : '' }}>
                        <span class="form-label" style="margin-bottom: 0;">👋 Accept Volunteers</span>
                    </label>
                    <small style="color: var(--gray-500); display: block; margin-top: 0.25rem; margin-left: 1.75rem;">
                        Allow people to sign up as volunteers for your campaign
                    </small>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="accepts_in_kind" value="1" id="acceptsInKind" {{ old('accepts_in_kind') ? 'checked' : '' }} onchange="toggleInKindNeeds()">
                        <span class="form-label" style="margin-bottom: 0;">📦 Accept In-Kind Donations</span>
                    </label>
                    <small style="color: var(--gray-500); display: block; margin-top: 0.25rem; margin-left: 1.75rem;">
                        Accept physical items or services instead of just money
                    </small>
                </div>

                <div class="form-group" id="inKindNeedsGroup" style="display: {{ old('accepts_in_kind') ? 'block' : 'none' }};">
                    <label class="form-label">What items do you need?</label>
                    <textarea name="in_kind_needs" class="form-control" rows="4" placeholder="List the items or services you need (e.g., Books, School supplies, Laptops, Medical equipment)">{{ old('in_kind_needs') }}</textarea>
                </div>
            </div>

            <!-- Step 4: Review & Submit -->
            <div class="form-step" data-step="4">
                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--gray-900);">✅ Review & Submit</h2>

                <div style="background: var(--gray-50); padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <h3 style="font-weight: 600; margin-bottom: 1rem;">📋 Campaign Summary</h3>
                    <div id="reviewSummary">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>

                <div style="background: #dbeafe; padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid #3b82f6;">
                    <h3 style="font-weight: 600; margin-bottom: 0.5rem; color: #1e40af;">📢 What happens next?</h3>
                    <ol style="margin-left: 1.5rem; color: #1e40af;">
                        <li>Your campaign will be submitted for review</li>
                        <li>Our team will review it within 24-48 hours</li>
                        <li>You'll receive an email notification once approved</li>
                        <li>Your campaign will go live and start accepting donations</li>
                    </ol>
                </div>

                <div class="form-group" style="margin-top: 1.5rem;">
                    <label style="display: flex; align-items: start; gap: 0.5rem;">
                        <input type="checkbox" required style="margin-top: 0.25rem;">
                        <span style="font-size: 0.875rem; color: var(--gray-700);">
                            I confirm that all information provided is accurate and I agree to the platform's terms and conditions. I understand that false or misleading information may result in campaign removal.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div style="display: flex; justify-content: space-between; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid var(--gray-200);">
                <button type="button" class="btn btn-outline" id="prevBtn" onclick="changeStep(-1)" style="display: none;">← Previous</button>
                <div style="flex: 1;"></div>
                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">Next →</button>
                <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">🚀 Submit Campaign</button>
            </div>
        </form>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 4;

function changeStep(direction) {
    const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    
    // Validate current step before moving forward
    if (direction === 1 && !validateStep(currentStep)) {
        return;
    }

    // Hide current step
    currentStepElement.classList.remove('active');
    
    // Update step
    currentStep += direction;
    
    // Show new step
    const newStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    newStepElement.classList.add('active');
    
    // Update step indicators
    updateStepIndicators();
    
    // Update buttons
    updateButtons();
    
    // Update review if on last step
    if (currentStep === 4) {
        updateReview();
    }
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepIndicators() {
    document.querySelectorAll('.step').forEach((step, index) => {
        const stepNum = index + 1;
        if (stepNum < currentStep) {
            step.classList.add('completed');
            step.classList.remove('active');
        } else if (stepNum === currentStep) {
            step.classList.add('active');
            step.classList.remove('completed');
        } else {
            step.classList.remove('active', 'completed');
        }
    });
}

function updateButtons() {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    prevBtn.style.display = currentStep === 1 ? 'none' : 'inline-block';
    nextBtn.style.display = currentStep === totalSteps ? 'none' : 'inline-block';
    submitBtn.style.display = currentStep === totalSteps ? 'inline-block' : 'none';
}

function validateStep(step) {
    const stepElement = document.querySelector(`.form-step[data-step="${step}"]`);
    const inputs = stepElement.querySelectorAll('input[required], select[required], textarea[required]');
    
    for (let input of inputs) {
        if (!input.value.trim()) {
            input.focus();
            alert('Please fill in all required fields');
            return false;
        }
    }
    return true;
}

function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    
    if (file) {
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

function updateReview() {
    const form = document.getElementById('campaignForm');
    const formData = new FormData(form);
    
    let html = '<div style="display: grid; gap: 1rem;">';
    
    html += `<div><strong>Title:</strong> ${formData.get('title') || 'Not provided'}</div>`;
    html += `<div><strong>Category:</strong> ${document.querySelector('select[name="category_id"] option:checked')?.text || 'Not selected'}</div>`;
    html += `<div><strong>Location:</strong> ${formData.get('location') || 'Not provided'}</div>`;
    html += `<div><strong>Goal:</strong> $${formData.get('goal_amount') || '0'}</div>`;
    html += `<div><strong>End Date:</strong> ${formData.get('end_date') || 'Not set'}</div>`;
    html += `<div><strong>Urgent:</strong> ${formData.get('is_urgent') ? 'Yes' : 'No'}</div>`;
    html += `<div><strong>Accepts Volunteers:</strong> ${formData.get('accepts_volunteers') ? 'Yes' : 'No'}</div>`;
    html += `<div><strong>Accepts In-Kind:</strong> ${formData.get('accepts_in_kind') ? 'Yes' : 'No'}</div>`;
    
    html += '</div>';
    
    document.getElementById('reviewSummary').innerHTML = html;
}
</script>
@endsection
