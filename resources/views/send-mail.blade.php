@extends('layout.master')

@section('title', 'Send Renewal Email')

@section('content')
    <!--start page wrapper -->
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Email</div>
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Send Renewal Email</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->

            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-1 text-primary">Send Renewal Email</h5>
                                    <p class="mb-0 font-13 text-secondary">Send renewal reminder to client</p>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm">
                                        <i class="bx bx-arrow-back"></i> Back to Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Service Information Card -->
                        <div class="card-body">
                            <div class="alert alert-info border-0 bg-light-info">
                                <div class="d-flex align-items-center">
                                    <div class="font-35 text-info"><i class='bx bx-info-circle'></i></div>
                                    <div class="ms-3">
                                        <h6 class="mb-0 text-info">Service Information</h6>
                                        <div class="mt-2">
                                            <strong>Service:</strong> {{ $service->service_name }} <br>
                                            <strong>Client:</strong> {{ $service->client->cname ?? 'N/A' }} <br>
                                            <strong>Vendor:</strong> {{ $service->vendor->name ?? 'N/A' }} <br>
                                            <strong>Expiry Date:</strong>
                                            <span
                                                class="
											@php
$daysLeft = \Carbon\Carbon::today()->diffInDays($service->end_date, false);
												echo $daysLeft <= 1 ? 'text-danger' : ($daysLeft <= 7 ? 'text-warning' : 'text-info'); @endphp
										">
                                                {{ $service->end_date->format('d M Y') }}
                                                @php
                                                    if ($daysLeft < 0) {
                                                        echo ' (Expired ' . abs($daysLeft) . ' days ago)';
                                                    } elseif ($daysLeft == 0) {
                                                        echo ' (Expires Today)';
                                                    } elseif ($daysLeft == 1) {
                                                        echo ' (Expires Tomorrow)';
                                                    } else {
                                                        echo ' (' . $daysLeft . ' days remaining)';
                                                    }
                                                @endphp
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Success/Error Messages -->
                            @if (session('success'))
                                <div class="alert alert-success border-0 bg-light-success alert-dismissible fade show">
                                    <div class="text-success">{{ session('success') }}</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger border-0 bg-light-danger">
                                    <div class="text-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <!-- Email Form -->
                            <form method="POST" action="{{ route('send-mail.send') }}">
                                @csrf
                                <input type="hidden" name="service_id" value="{{ $service->id }}">

                                <div class="row g-3">
                                    <!-- To Email Field -->
                                    <div class="col-md-12">
                                        <label for="to_email" class="form-label">To <span
                                                class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('to_email') is-invalid @enderror"
                                            id="to_email" name="to_email"
                                            value="{{ old('to_email', $service->client->email ?? '') }}"
                                            placeholder="Enter recipient email address" required>
                                        @error('to_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- CC Email Field -->
                                    <div class="col-md-12">
                                        <label for="cc_emails" class="form-label">CC (Optional)</label>
                                        <input type="text" class="form-control @error('cc_emails') is-invalid @enderror"
                                            id="cc_emails" name="cc_emails" value="{{ old('cc_emails') }}"
                                            placeholder="Enter CC email addresses separated by commas">
                                        <div class="form-text">Separate multiple email addresses with commas (e.g.,
                                            email1@example.com, email2@example.com)</div>
                                        @error('cc_emails')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Subject Field -->
                                    <div class="col-md-12">
                                        <label for="subject" class="form-label">Subject <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                            id="subject" name="subject" value="{{ old('subject', $defaultSubject) }}"
                                            placeholder="Enter email subject" required>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Message Field -->
                                    <div class="col-md-12">
                                        <label for="message" class="form-label">Message <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control ckeditor @error('message') is-invalid @enderror" id="message" name="message"
                                            placeholder="Enter your email message..." rows="8" required>{{ old(
                                                'message',
                                                'Dear ' .
                                                    ($service->client->cname ?? 'Valued Client') .
                                                    ',
                                            
                                            We hope this email finds you well. We are writing to remind you that your service is approaching its renewal date.
                                            
                                            Please review the service details above and contact us to proceed with the renewal process.
                                            
                                            Thank you for your continued business.
                                            
                                            Best regards,
                                            Technofra Team',
                                            ) }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div class="col-md-12">
                                        <div class="d-flex gap-3">
                                            <button type="submit" class="btn btn-primary px-4">
                                                <i class="bx bx-mail-send"></i> Send Email
                                            </button>
                                            <a href="{{ route('dashboard') }}" class="btn btn-light px-4">
                                                <i class="bx bx-x"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!--end page wrapper -->

    <!-- CKEditor CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize CKEditor
            const textarea = document.getElementById('message');
            if (textarea) {
                ClassicEditor
                    .create(textarea, {
                        toolbar: [
                            'heading', '|',
                            'bold', 'italic', 'underline', '|',
                            'bulletedList', 'numberedList', '|',
                            'outdent', 'indent', '|',
                            'link', 'blockQuote', 'insertTable', '|',
                            'undo', 'redo'
                        ],
                        height: 300
                    })
                    .catch(error => {
                        console.error('Error initializing CKEditor:', error);
                    });
            }
        });
    </script>
@endsection
