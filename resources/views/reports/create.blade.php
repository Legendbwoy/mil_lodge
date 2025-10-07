@extends('layouts.client')

@section('title', 'Report an Issue - Akafia')
@section('page-title', 'Report an Issue')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Report an Issue or Suggestion</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" id="reportForm">
                        @csrf

                        <!-- Report Type -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label fw-bold">What would you like to report? *</label>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check card report-type-card">
                                            <input class="form-check-input" type="radio" name="report_type" id="amenity_issue" value="amenity_issue" required>
                                            <label class="form-check-label card-body text-center" for="amenity_issue">
                                                <i class="fas fa-tv fa-2x text-primary mb-2"></i>
                                                <h6>Amenity Issue</h6>
                                                <small class="text-muted">Problems with facilities or amenities</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check card report-type-card">
                                            <input class="form-check-input" type="radio" name="report_type" id="repair" value="repair" required>
                                            <label class="form-check-label card-body text-center" for="repair">
                                                <i class="fas fa-tools fa-2x text-warning mb-2"></i>
                                                <h6>Repair Needed</h6>
                                                <small class="text-muted">Something needs fixing or maintenance</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check card report-type-card">
                                            <input class="form-check-input" type="radio" name="report_type" id="renovation" value="renovation" required>
                                            <label class="form-check-label card-body text-center" for="renovation">
                                                <i class="fas fa-paint-roller fa-2x text-success mb-2"></i>
                                                <h6>Renovation Suggestion</h6>
                                                <small class="text-muted">Ideas for improvements or upgrades</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('report_type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Accommodation Selection -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="accommodation_id" class="form-label">Related Accommodation (Optional)</label>
                                <select class="form-select" id="accommodation_id" name="accommodation_id">
                                    <option value="">Select accommodation...</option>
                                    @foreach($accommodations as $accommodation)
                                        <option value="{{ $accommodation->id }}" {{ old('accommodation_id') == $accommodation->id ? 'selected' : '' }}>
                                            {{ $accommodation->name }} - {{ $accommodation->location }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('accommodation_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="location" class="form-label">Specific Location *</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" 
                                       placeholder="e.g., Room 101, Main Lobby, Swimming Pool Area..." required>
                                @error('location')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Title and Description -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="title" class="form-label">Issue Title *</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" 
                                       placeholder="Brief description of the issue..." required>
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="description" class="form-label">Detailed Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="5" 
                                          placeholder="Please provide detailed information about the issue, suggestion, or repair needed..." required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Priority -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="priority" class="form-label">Priority Level *</label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="">Select priority...</option>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - Minor issue, no urgency</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium - Should be addressed soon</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Important, needs attention</option>
                                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent - Critical, immediate attention needed</option>
                                </select>
                                @error('priority')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Upload -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="images" class="form-label">Upload Photos (Optional)</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple 
                                       accept="image/*">
                                <div class="form-text">
                                    You can upload multiple images. Maximum file size: 2MB per image. Supported formats: JPEG, PNG, JPG, GIF.
                                </div>
                                <div id="imagePreview" class="mt-2"></div>
                                @error('images.*')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Report
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Need Help?</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Emergency Contacts</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-phone text-primary me-2"></i> Front Desk: <strong>+233 XX XXX XXXX</strong></li>
                                <li><i class="fas fa-phone text-danger me-2"></i> Emergency: <strong>+233 XX XXX XXXX</strong></li>
                                <li><i class="fas fa-envelope text-info me-2"></i> Email: <strong>support@akafia.com</strong></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>What to Include</h6>
                            <ul class="small">
                                <li>Clear description of the issue</li>
                                <li>Specific location details</li>
                                <li>Photos if available</li>
                                <li>Your contact information</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Report type card selection
    const reportTypeCards = document.querySelectorAll('.report-type-card');
    reportTypeCards.forEach(card => {
        card.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Remove active class from all cards
            reportTypeCards.forEach(c => c.classList.remove('active'));
            // Add active class to clicked card
            this.classList.add('active');
        });
    });

    // Image preview
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        
        if (this.files) {
            Array.from(this.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-md-3 mb-2';
                        col.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-thumbnail" style="height: 100px; object-fit: cover;">
                                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0" onclick="this.parentElement.parentElement.remove()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        imagePreview.appendChild(col);
                    };
                    
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // Form validation
    const form = document.getElementById('reportForm');
    form.addEventListener('submit', function(e) {
        let valid = true;
        
        // Check report type
        const reportType = document.querySelector('input[name="report_type"]:checked');
        if (!reportType) {
            alert('Please select a report type');
            valid = false;
        }
        
        // Check location
        const location = document.getElementById('location');
        if (!location.value.trim()) {
            alert('Please specify the location');
            location.focus();
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
});
</script>

<style>
.report-type-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.report-type-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
}

.report-type-card.active {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.report-type-card .form-check-input {
    position: absolute;
    opacity: 0;
}

#imagePreview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
</style>
@endsection