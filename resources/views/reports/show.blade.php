@extends('layouts.admin')

@section('title', 'Report Details - Akafia')
@section('page-title', 'Report Details')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Report #{{ $report->id }}</h5>
                    <span class="badge {{ $report->status_badge }} fs-6">
                        {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Report Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Basic Information</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="120">Type:</th>
                                    <td>{{ $report->report_type_label }}</td>
                                </tr>
                                <tr>
                                    <th>Title:</th>
                                    <td>{{ $report->title }}</td>
                                </tr>
                                <tr>
                                    <th>Location:</th>
                                    <td>{{ $report->location }}</td>
                                </tr>
                                <tr>
                                    <th>Priority:</th>
                                    <td>
                                        <span class="badge {{ $report->priority_badge }}">
                                            {{ ucfirst($report->priority) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Timeline</h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="120">Submitted:</th>
                                    <td>{{ $report->created_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                                @if($report->resolved_at)
                                <tr>
                                    <th>Resolved:</th>
                                    <td>{{ $report->resolved_at->format('M d, Y \a\t h:i A') }}</td>
                                </tr>
                                @endif
                                @if($report->accommodation)
                                <tr>
                                    <th>Accommodation:</th>
                                    <td>{{ $report->accommodation->name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Description</h6>
                            <div class="border rounded p-3 bg-light">
                                {{ $report->description }}
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    @if($report->images && count($report->images) > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Attached Images</h6>
                            <div class="row">
                                @foreach($report->images as $image)
                                <div class="col-md-3 mb-2">
                                    <a href="{{ Storage::url($image) }}" target="_blank">
                                        <img src="{{ Storage::url($image) }}" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($report->admin_notes)
                    <div class="row">
                        <div class="col-12">
                            <h6>Admin Response</h6>
                            <div class="alert alert-info">
                                <i class="fas fa-comment-dots me-2"></i>
                                {{ $report->admin_notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Reports
                    </a>
                    @if($report->status === 'pending')
                    <span class="text-muted ms-3">
                        <i class="fas fa-clock me-1"></i>Your report is pending review
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection