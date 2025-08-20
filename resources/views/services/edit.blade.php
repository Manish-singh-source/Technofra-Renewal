@extends('/layout/master')
@section('content')
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		@if(session('success'))
			<div class="alert alert-success alert-dismissible fade show" role="alert">
				{{ session('success') }}
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		@if($errors->any())
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<ul class="mb-0">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		@endif

		<!--start stepper one-->
		<h6 class="text-uppercase">Service Form</h6>
		<hr>
		<div id="stepper1" class="bs-stepper">
			<div class="card">
				<div class="card-body p-4">
					<h5 class="mb-4">Edit Service</h5>
					<form class="row g-3" method="POST" action="{{ route('services.update', $service->id) }}">
						@csrf
						@method('PUT')
						
						<div class="col-md-6">
							<label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
							<select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
								<option value="">Choose a client...</option>
								@foreach($clients as $client)
									<option value="{{ $client->id }}" {{ old('client_id', $service->client_id) == $client->id ? 'selected' : '' }}>
										{{ $client->cname }}
									</option>
								@endforeach
							</select>
							@error('client_id')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="col-md-6">
							<label for="vendor_id" class="form-label">Vendor <span class="text-danger">*</span></label>
							<select class="form-select @error('vendor_id') is-invalid @enderror" id="vendor_id" name="vendor_id" required>
								<option value="">Choose a vendor...</option>
								@foreach($vendors as $vendor)
									<option value="{{ $vendor->id }}" {{ old('vendor_id', $service->vendor_id) == $vendor->id ? 'selected' : '' }}>
										{{ $vendor->name }}
									</option>
								@endforeach
							</select>
							@error('vendor_id')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="col-md-12">
							<label for="service_name" class="form-label">Service Name <span class="text-danger">*</span></label>
							<input type="text" class="form-control @error('service_name') is-invalid @enderror"
								   id="service_name" name="service_name" value="{{ old('service_name', $service->service_name) }}"
								   placeholder="Enter service name" required>
							@error('service_name')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="col-md-12">
							<label for="service_details" class="form-label">Service Details</label>
							<textarea class="form-control ckeditor @error('service_details') is-invalid @enderror"
									  id="service_details" name="service_details"
									  placeholder="Enter detailed description of the service..." rows="6">{{ old('service_details', $service->service_details) }}</textarea>
							@error('service_details')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="col-md-6">
							<label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
							<input type="date" class="form-control @error('start_date') is-invalid @enderror" 
								   id="start_date" name="start_date" value="{{ old('start_date', $service->start_date->format('Y-m-d')) }}" required>
							@error('start_date')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="col-md-6">
							<label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
							<input type="date" class="form-control @error('end_date') is-invalid @enderror" 
								   id="end_date" name="end_date" value="{{ old('end_date', $service->end_date->format('Y-m-d')) }}" required>
							@error('end_date')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="col-md-6">
							<label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
							<input type="number" class="form-control @error('amount') is-invalid @enderror" 
								   id="amount" name="amount" value="{{ old('amount', $service->amount) }}" 
								   placeholder="0.00" step="0.01" min="0" required>
							@error('amount')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="col-md-6">
							<label for="status" class="form-label">Status <span class="text-danger">*</span></label>
							<select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
								<option value="active" {{ old('status', $service->status) == 'active' ? 'selected' : '' }}>Active</option>
								<option value="inactive" {{ old('status', $service->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
								<option value="pending" {{ old('status', $service->status) == 'pending' ? 'selected' : '' }}>Pending</option>
								<option value="expired" {{ old('status', $service->status) == 'expired' ? 'selected' : '' }}>Expired</option>
							</select>
							@error('status')
								<div class="invalid-feedback">{{ $message }}</div>
							@enderror
						</div>

						<div class="col-md-12">
							<div class="d-md-flex d-grid align-items-center gap-3">
								<button type="submit" class="btn btn-primary px-4">Update Service</button>
								<a href="{{ route('services.index') }}" class="btn btn-light px-4">Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--end stepper one-->
	</div>
</div>
<!--end page wrapper -->
<!--start overlay-->
<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button-->
<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->

<!-- CKEditor CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor
    const textarea = document.getElementById('service_details');
    if (textarea) {
        ClassicEditor
            .create(textarea, {
                toolbar: [
                    'heading', '|',
                    'bold', 'italic', 'link', '|',
                    'bulletedList', 'numberedList', '|',
                    'outdent', 'indent', '|',
                    'blockQuote', 'insertTable', '|',
                    'undo', 'redo'
                ]
            })
            .catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
    }
});
</script>
@endsection
