@extends('layouts.admin')

@section('page-title', 'Add Accommodation')

@section('content')
<div class="container-fluid">
    <div class="pooded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Create New Room</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.accommodations.index') }}">Rooms</a></li>
                            <li class="breadcrumb-item"><a href="#!">Create</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts: bootstrap (visible) and SweetAlert (toast) -->
        @if(session('success')) 
            <div id="server-success" class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {!! session('success') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error')) 
            <div id="server-error" class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {!! session('error') !!}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Validation errors list -->
        @if ($errors->any()) 
            <div id="validation-errors" class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Room Information</h5>
                    </div>
                    <div class="card-body">
                        <form id="accommodationForm" action="{{ route('admin.accommodations.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Room Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required
                                            value="{{ old('name') }}" placeholder="Enter room name (e.g., Akafia Lodge - Block A - Room 01)">
                                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lodge_name">Lodge *</label>
                                        <select class="form-control @error('lodge_name') is-invalid @enderror" id="lodge_name" name="lodge_name" required>
                                            <option value="">Select Lodge</option>
                                            <option value="Akafia Lodge" {{ old('lodge_name') == 'Akafia Lodge' ? 'selected' : '' }}>Akafia Lodge</option>
                                            <option value="Oppong Peprah Lodge" {{ old('lodge_name') == 'Oppong Peprah Lodge' ? 'selected' : '' }}>Oppong Peprah Lodge</option>
                                        </select>
                                        @error('lodge_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="block_name">Block/Terrace *</label>
                                        <select class="form-control @error('block_name') is-invalid @enderror" id="block_name" name="block_name" required>
                                            <option value="">Select Block/Terrace</option>
                                            <option value="Block A" {{ old('block_name') == 'Block A' ? 'selected' : '' }}>Block A</option>
                                            <option value="Block B" {{ old('block_name') == 'Block B' ? 'selected' : '' }}>Block B</option>
                                            <option value="Block C" {{ old('block_name') == 'Block C' ? 'selected' : '' }}>Block C</option>
                                            <option value="Upper Terrace" {{ old('block_name') == 'Upper Terrace' ? 'selected' : '' }}>Upper Terrace</option>
                                            <option value="Lower Terrace" {{ old('block_name') == 'Lower Terrace' ? 'selected' : '' }}>Lower Terrace</option>
                                        </select>
                                        @error('block_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Room Type *</label>
                                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                            <option value="">Select Type</option>
                                            <option value="barracks" {{ old('type') == 'barracks' ? 'selected' : '' }}>Barracks</option>
                                            <option value="family_quarters" {{ old('type') == 'family_quarters' ? 'selected' : '' }}>Family Quarters</option>
                                            <option value="voq" {{ old('type') == 'voq' ? 'selected' : '' }}>Visiting Officers Quarters</option>
                                            <option value="tlq" {{ old('type') == 'tlq' ? 'selected' : '' }}>Temporary Lodging</option>
                                            <option value="recreation_lodge" {{ old('type') == 'recreation_lodge' ? 'selected' : '' }}>Recreation Lodge</option>
                                        </select>
                                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location *</label>
                                        <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" required
                                            value="{{ old('location') }}" placeholder="Enter location (e.g., Akafia Lodge, Block A)">
                                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_per_night">Price Per Bed (GH¢) *</label>
                                        <input type="number" step="0.01" class="form-control @error('price_per_night') is-invalid @enderror" id="price_per_night"
                                            name="price_per_night" required value="{{ old('price_per_night') }}"
                                            placeholder="Enter price per bed per night">
                                        @error('price_per_night')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description *</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4"
                                    required placeholder="Enter room description">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="total_beds">Total Beds *</label>
                                        <select class="form-control @error('total_beds') is-invalid @enderror" id="total_beds" name="total_beds" required>
                                            <option value="">Select Total Beds</option>
                                            <option value="3" {{ old('total_beds', 3) == 3 ? 'selected' : '' }}>3 Beds (Oppong Peprah Lodge)</option>
                                            <option value="4" {{ old('total_beds', 4) == 4 ? 'selected' : '' }}>4 Beds (Akafia Lodge)</option>
                                        </select>
                                        <small class="form-text text-muted">Akafia Lodge: 4 beds per room<br>Oppong Peprah Lodge: 3 beds per room</small>
                                        @error('total_beds')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="available_beds">Available Beds *</label>
                                        <input type="number" class="form-control @error('available_beds') is-invalid @enderror" id="available_beds" name="available_beds"
                                            required value="{{ old('available_beds', 3) }}" min="0" max="4">
                                        <small class="form-text text-muted">Number of beds currently available for booking</small>
                                        @error('available_beds')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="max_guests">Max Guests *</label>
                                        <input type="number" class="form-control @error('max_guests') is-invalid @enderror" id="max_guests" name="max_guests"
                                            required value="{{ old('max_guests', 3) }}" min="1" max="10">
                                        <small class="form-text text-muted">Maximum number of guests allowed</small>
                                        @error('max_guests')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bedrooms">Bedrooms *</label>
                                        <input type="number" class="form-control @error('bedrooms') is-invalid @enderror" id="bedrooms" name="bedrooms"
                                            required value="{{ old('bedrooms', 1) }}" min="1">
                                        @error('bedrooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bathrooms">Bathrooms *</label>
                                        <input type="number" class="form-control @error('bathrooms') is-invalid @enderror" id="bathrooms" name="bathrooms"
                                            required value="{{ old('bathrooms', 1) }}" min="1" step="0.5">
                                        @error('bathrooms')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="square_feet">Square Feet</label>
                                        <input type="number" class="form-control @error('square_feet') is-invalid @enderror" id="square_feet" name="square_feet"
                                            value="{{ old('square_feet') }}" placeholder="Optional">
                                        @error('square_feet')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="amenities">Amenities</label>
                                        <select class="form-control select2-multiple" id="amenities" name="amenities[]" multiple>
                                            <option value="wifi">WiFi</option>
                                            <option value="air_conditioning">Air Conditioning</option>
                                            <option value="tv">TV</option>
                                            <option value="kitchen">Kitchen</option>
                                            <option value="parking">Parking</option>
                                            <option value="pool">Swimming Pool</option>
                                            <option value="gym">Gym</option>
                                            <option value="breakfast">Breakfast</option>
                                            <option value="laundry">Laundry</option>
                                            <option value="security">Security</option>
                                        </select>
                                        <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple amenities</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                                value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Featured Room
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available"
                                                value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_available">
                                                Available for Booking
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="family_friendly" name="family_friendly"
                                                value="1" {{ old('family_friendly') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="family_friendly">
                                                Family Friendly
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <label for="images">Room Images</label>
                                <input type="file" class="form-control-file @error('images.*') is-invalid @enderror" id="images" name="images[]" multiple accept="image/*">
                                <small class="form-text text-muted">You can select multiple images (Max: 5 images, 2MB each)</small>
                                @error('images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                <div id="imagePreview" class="mt-2 row"></div>
                            </div>

                            <div class="form-group mt-4">
                                <button id="submitBtn" type="submit" class="btn btn-primary">
                                    <i class="feather icon-save"></i> Create Room
                                </button>
                                <a href="{{ route('admin.accommodations.index') }}" class="btn btn-secondary">
                                    <i class="feather icon-x"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 for nicer toast messages -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 for multiple select -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2
    $('.select2-multiple').select2({
        placeholder: "Select amenities",
        allowClear: true
    });

    // show server messages via sweetalert (also visible as bootstrap alerts above)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: @json(session('success')),
            toast: true,
            position: 'top-end',
            timer: 3500,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: @json(session('error')),
            toast: true,
            position: 'top-end',
            timer: 5000,
            showConfirmButton: false
        });
    @endif

    // If there are validation errors, show the first in a sweetalert toast
    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Validation error',
            text: @json($errors->first()),
            toast: true,
            position: 'top-end',
            timer: 6000,
            showConfirmButton: false
        });
        // Scroll to errors
        const errEl = document.getElementById('validation-errors');
        if (errEl) { errEl.scrollIntoView({ behavior: 'smooth' }); }
    @endif

    // Lodge and block/total beds logic
    const lodgeSelect = document.getElementById('lodge_name');
    const blockSelect = document.getElementById('block_name');
    const totalBedsSelect = document.getElementById('total_beds');
    const availableBedsInput = document.getElementById('available_beds');
    const maxGuestsInput = document.getElementById('max_guests');

    // Update blocks based on lodge selection
    lodgeSelect.addEventListener('change', function() {
        const lodge = this.value;
        
        // Clear and update block options
        blockSelect.innerHTML = '<option value="">Select Block/Terrace</option>';
        
        if (lodge === 'Akafia Lodge') {
            blockSelect.innerHTML += `
                <option value="Block A">Block A</option>
                <option value="Block B">Block B</option>
                <option value="Block C">Block C</option>
            `;
            // Set default total beds for Akafia Lodge
            totalBedsSelect.value = '4';
            availableBedsInput.max = 4;
            maxGuestsInput.value = 4;
        } else if (lodge === 'Oppong Peprah Lodge') {
            blockSelect.innerHTML += `
                <option value="Upper Terrace">Upper Terrace</option>
                <option value="Lower Terrace">Lower Terrace</option>
            `;
            // Set default total beds for Oppong Peprah Lodge
            totalBedsSelect.value = '3';
            availableBedsInput.max = 3;
            maxGuestsInput.value = 3;
        }
        
        // Update available beds if it exceeds new max
        if (parseInt(availableBedsInput.value) > parseInt(availableBedsInput.max)) {
            availableBedsInput.value = availableBedsInput.max;
        }
    });

    // Update available beds max based on total beds
    totalBedsSelect.addEventListener('change', function() {
        availableBedsInput.max = this.value;
        if (parseInt(availableBedsInput.value) > parseInt(this.value)) {
            availableBedsInput.value = this.value;
        }
    });

    // Auto-generate room name based on selections
    function generateRoomName() {
        const lodge = lodgeSelect.value;
        const block = blockSelect.value;
        
        if (lodge && block) {
            const nameInput = document.getElementById('name');
            if (!nameInput.value || nameInput.value.includes('Room')) {
                nameInput.value = `${lodge} - ${block} - Room 01`;
            }
        }
    }

    lodgeSelect.addEventListener('change', generateRoomName);
    blockSelect.addEventListener('change', generateRoomName);

    // Auto-generate location
    function generateLocation() {
        const lodge = lodgeSelect.value;
        const block = blockSelect.value;
        
        if (lodge && block) {
            const locationInput = document.getElementById('location');
            if (!locationInput.value) {
                locationInput.value = `${lodge}, ${block}`;
            }
        }
    }

    lodgeSelect.addEventListener('change', generateLocation);
    blockSelect.addEventListener('change', generateLocation);

    // Client-side image preview + simple checks (count <=5, size <= 2MB)
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');
    const form = document.getElementById('accommodationForm');
    const submitBtn = document.getElementById('submitBtn');

    imageInput && imageInput.addEventListener('change', function() {
        imagePreview.innerHTML = '';
        const files = Array.from(this.files || []);
        if (files.length > 5) {
            Swal.fire({ icon: 'warning', title: 'Too many images', text: 'Maximum 5 images allowed.'});
        }
        files.forEach(file => {
            if (!file.type.startsWith('image/')) return;
            if (file.size > 2 * 1024 * 1024) {
                // show warning but still allow user to remove or change
                const warn = document.createElement('div');
                warn.className = 'col-12 text-danger mb-2';
                warn.textContent = `Warning: ${file.name} is larger than 2MB.`;
                imagePreview.appendChild(warn);
            }
            const reader = new FileReader();
            reader.onload = e => {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-2';
                col.innerHTML = `
                    <div class="position-relative">
                        <img src="${e.target.result}" class="img-thumbnail" style="height: 120px; object-fit: cover; width: 100%;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 remove-preview-btn" style="transform: translate(30%,-30%);">
                            <i class="feather icon-x"></i>
                        </button>
                    </div>
                `;
                imagePreview.appendChild(col);
                // remove button
                col.querySelector('.remove-preview-btn').addEventListener('click', () => col.remove());
            };
            reader.readAsDataURL(file);
        });
    });

    // Submit handler: client-side checks before sending form
    form && form.addEventListener('submit', function(e) {
        // Disable button to prevent multiple submits
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

        // Check image count/size
        const files = Array.from(imageInput.files || []);
        if (files.length > 5) {
            e.preventDefault();
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="feather icon-save"></i> Create Room';
            Swal.fire({ icon: 'warning', title: 'Too many images', text: 'Maximum 5 images allowed.'});
            return false;
        }
        for (const file of files) {
            if (file.size > 2 * 1024 * 1024) {
                e.preventDefault();
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="feather icon-save"></i> Create Room';
                Swal.fire({ icon: 'warning', title: 'File too large', text: `${file.name} exceeds 2 MB.`});
                return false;
            }
        }

        // Validate available beds don't exceed total beds
        const totalBeds = parseInt(totalBedsSelect.value);
        const availableBeds = parseInt(availableBedsInput.value);
        if (availableBeds > totalBeds) {
            e.preventDefault();
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="feather icon-save"></i> Create Room';
            Swal.fire({ 
                icon: 'warning', 
                title: 'Invalid bed count', 
                text: 'Available beds cannot exceed total beds.' 
            });
            return false;
        }

        // allow submit to continue
        return true;
    });

    // Auto-hide bootstrap alerts after 5s
    document.querySelectorAll('.alert').forEach(a => {
        setTimeout(() => {
            try {
                let bsAlert = bootstrap.Alert.getOrCreateInstance(a);
                bsAlert.close();
            } catch(e) {
                a.style.display = 'none';
            }
        }, 5000);
    });

    // Initialize form with default values if no old input
    @if(!old('lodge_name'))
        // Set default lodge and trigger change to populate blocks
        lodgeSelect.value = 'Akafia Lodge';
        lodgeSelect.dispatchEvent(new Event('change'));
    @endif
});
</script>

<style>
.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    min-height: 38px;
}

.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}
</style>
@endsection