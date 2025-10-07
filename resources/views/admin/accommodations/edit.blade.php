@extends('layouts.admin')

@section('page-title', 'Add Accommodation')

@section('content')
<div class="container-fluid">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">Edit Accommodation: {{ $accommodation->name }}</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.accommodations.index') }}">Accommodations</a></li>
                            <li class="breadcrumb-item"><a href="#!">Edit</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Accommodation Information</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.accommodations.update', $accommodation->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Accommodation Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" required 
                                               value="{{ old('name', $accommodation->name) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Accommodation Type *</label>
                                        <select class="form-control" id="type" name="type" required>
                                            <option value="barracks" {{ $accommodation->type == 'barracks' ? 'selected' : '' }}>Barracks</option>
                                            <option value="family_quarters" {{ $accommodation->type == 'family_quarters' ? 'selected' : '' }}>Family Quarters</option>
                                            <option value="voq" {{ $accommodation->type == 'voq' ? 'selected' : '' }}>Visiting Officers Quarters</option>
                                            <option value="tlq" {{ $accommodation->type == 'tlq' ? 'selected' : '' }}>Temporary Lodging</option>
                                            <option value="recreation_lodge" {{ $accommodation->type == 'recreation_lodge' ? 'selected' : '' }}>Recreation Lodge</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location *</label>
                                        <input type="text" class="form-control" id="location" name="location" required
                                               value="{{ old('location', $accommodation->location) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_per_night">Price Per Night ($) *</label>
                                        <input type="number" step="0.01" class="form-control" id="price_per_night" 
                                               name="price_per_night" required value="{{ old('price_per_night', $accommodation->price_per_night) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $accommodation->description) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="max_guests">Max Guests *</label>
                                        <input type="number" class="form-control" id="max_guests" name="max_guests" 
                                               required value="{{ old('max_guests', $accommodation->max_guests) }}" min="1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bedrooms">Bedrooms *</label>
                                        <input type="number" class="form-control" id="bedrooms" name="bedrooms" 
                                               required value="{{ old('bedrooms', $accommodation->bedrooms) }}" min="1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bathrooms">Bathrooms *</label>
                                        <input type="number" class="form-control" id="bathrooms" name="bathrooms" 
                                               required value="{{ old('bathrooms', $accommodation->bathrooms) }}" min="1" step="0.5">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="square_feet">Square Feet</label>
                                        <input type="number" class="form-control" id="square_feet" name="square_feet" 
                                               value="{{ old('square_feet', $accommodation->square_feet) }}" placeholder="Optional">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                               value="1" {{ $accommodation->is_featured ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Accommodation
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_available" name="is_available" 
                                               value="1" {{ $accommodation->is_available ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_available">
                                            Available for Booking
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="family_friendly" name="family_friendly" 
                                               value="1" {{ $accommodation->family_friendly ? 'checked' : '' }}>
                                        <label class="form-check-label" for="family_friendly">
                                            Family Friendly
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="feather icon-save"></i> Update Accommodation
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