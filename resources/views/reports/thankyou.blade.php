@extends('layouts.client')

@section('title', 'Report Submitted - Akafia')
@section('page-title', 'Report Submitted Successfully')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    
                    <h3 class="card-title text-success mb-3">Thank You!</h3>
                    <p class="card-text lead mb-4">
                        Your report has been submitted successfully. We have received your 
                        <strong>{{ $report->report_type_label }}</strong> and will address it as soon as possible.
                    </p>

                    <div class="alert alert-info text-start mb-4">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Report Details</h6>
                        <hr>
                        <p class="mb-1"><strong>Reference #:</strong> #{{ $report->id }}</p>
                        <p class="mb-1"><strong>Title:</strong> {{ $report->title }}</p>
                        <p class="mb-1"><strong>Location:</strong> {{ $report->location }}</p>
                        <p class="mb-1"><strong>Priority:</strong> 
                            <span class="badge {{ $report->priority_badge }}">{{ ucfirst($report->priority) }}</span>
                        </p>
                        <p class="mb-0"><strong>Submitted:</strong> {{ $report->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ route('reports.create') }}" class="btn btn-primary me-md-2">
                            <i class="fas fa-plus me-2"></i>Submit Another Report
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View My Reports
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Back to Home
                        </a>
                    </div>

                    <div class="mt-4 text-muted">
                        <small>
                            <i class="fas fa-clock me-1"></i>
                            We typically respond within 24-48 hours. For urgent matters, 
                            please contact our front desk directly.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection