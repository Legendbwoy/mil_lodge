
@extends('layouts.admin')

@section('page-title', 'Show Accommodations')

@section('content')
<div class="container-fluid">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <div class="page-header-title">
                            <h5 class="m-b-10">All Accommodations</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#!">Accommodations</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Accommodations List</h5>
                        <a href="{{ route('admin.accommodations.create') }}" class="btn btn-primary btn-sm">
                            <i class="feather icon-plus"></i> Add New Accommodation
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Type</th>
                                        <th>Price/Night</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($accommodations as $accommodation)
                                        <tr>
                                            <td>{{ $accommodation->id }}</td>
                                            <td>
                                                <img src="{{ $accommodation->featured_image ?: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80' }}" 
                                                     alt="{{ $accommodation->name }}" 
                                                     style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            </td>
                                            <td>
                                                <strong>{{ $accommodation->name }}</strong>
                                                @if($accommodation->is_featured)
                                                    <span class="badge badge-warning ml-1">Featured</span>
                                                @endif
                                            </td>
                                            <td>{{ $accommodation->location }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst($accommodation->type) }}</span>
                                            </td>
                                            <td>${{ number_format($accommodation->price_per_night, 2) }}</td>
                                            <td>
                                                <span class="badge badge-{{ $accommodation->is_available ? 'success' : 'danger' }}">
                                                    {{ $accommodation->is_available ? 'Available' : 'Unavailable' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.accommodations.edit', $accommodation->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="feather icon-edit"></i>
                                                    </a>
                                                    <a href="#" 
                                                       class="btn btn-sm btn-outline-info" title="View Details">
                                                        <i class="feather icon-eye"></i>
                                                    </a>
                                                    <form action="#" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure?')" title="Delete">
                                                            <i class="feather icon-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No accommodations found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $accommodations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection